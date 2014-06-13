<?php
/*
 * Handles the redirection from ChargeBee after checkout using ChargeBee Hosted Page.
 */
$cboptions = get_option("chargebee");
$state = "";
try {
 $result = ChargeBee_HostedPage::retrieve($_GET['id']);
 $hosted_page = $result->hostedPage();
 $user = wp_get_current_user();
 if( $user->ID != $hosted_page->content()->customer()->id) {
   echo '<div class="cb-flash"><span class="cb-text-failure">Error in Processing </span></div>'; 
   return;
 }
 if( $hosted_page->state == "succeeded" ) {
   do_action('cb_update_result', $result->hostedPage()->content());
   $state = "checkout_success";
 } else if ( $hosted_page->state == "cancelled" ) {
   $state = "checkout_cancelled";
 }
 redirect_to_url( generate_page_link(get_permalink($cboptions['plan_page']), "state=" . $state) ) ;
} catch (ChargeBee_APIError $e) {
   echo '<div class="cb-flash"><span class="cb-text-failure">Error in Processing </span></div>';
} ?>

