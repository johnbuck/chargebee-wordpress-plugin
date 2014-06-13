<?php
/*
 * To carry out checkout process, redirect url should be configured at ChargeBee settings page.
 */
try { 
 $url = null;
 if( is_user_logged_in() ) {
   $user = wp_get_current_user();
   $cboptions = get_option("chargebee");
   $cb_customer = apply_filters("cb_get_customer", $user->ID);
   $cb_subscription = apply_filters("cb_get_subscription", $user->ID);
   if( isset($cb_subscription) ) {
      // Redirecting the user to home page if the user asked to switch to the same current plan id.
      if( $cb_subscription->planId == $cb_plan_id ) {
        redirect_to_url(site_url());
        return;
      }
   } 
   $checkout_existing = False;
   $checkout_new = False;
   if( $cb_customer != null && isset($cb_customer->cardStatus) ){
      // ChargeBee customer is present and found that no_card or expired_card then taking to checkout existing hosted page.
      if ($cb_customer->cardStatus == "no_card" || $cb_customer->cardStatus == "expired") {
         $checkout_existing = True;
      }
    } else {
      // if ChargeBee object not found then taking customer to checkout new hosted page.
      $checkout_new = True;
   }
   if( $checkout_existing ) {
       $result = ChargeBee_HostedPage::checkoutExisting(array("subscription" => array( "id" => $user->ID, "planId" => $cb_plan_id ),
                                                              "customer" => array("email" => $user->user_email), "embed" => "false"));
       $url = $result->hostedPage()->url;
       redirect_to_url($url);
   } else {
       if( $checkout_new ) {
           // customer will be taken to ChargeBee checkout new hosted page if no ChargeBee customer details found in wp_usermeta.
           $result = ChargeBee_HostedPage::checkoutNew(array("subscription" => array("id" => $user->ID, "planId" => $cb_plan_id),
                                                             "customer" => array("email" => $user->user_email), "embed" => "false") );
           $url = $result->hostedPage()->url;
           redirect_to_url($url); 
       } else {
          // ChargeBee Update Subscription call is called if card is valid in ChargeBee customer object.
          $cb_subscription = apply_filters("cb_get_subscription",$user->ID);
          $result = ChargeBee_Subscription::update($cb_subscription->id, array("planId" => $cb_plan_id));
          do_action('cb_update_result', $result);
          $url = generate_page_link(get_permalink($cboptions['plan_page']), "state=checkout_success");
          redirect_to_url($url); 
       }
   }
 } else {
   // if customer not logged in then redirecting him to login page.
   $url = wp_login_url();
   redirect_to_url($url);
 }
} catch(ChargeBee_APIError $e) {
  echo  "<div class='cb-flash'><span class='cb-text-failure'>Couldn't change your subscription. Please contact site owner.</span></div>";
} ?>
