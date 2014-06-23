<?php
/*
 * Creates a ChargeBee Customer Portal link and redirects the customer to their ChargeBee Customer Portal Page. 
 */
if( !is_user_logged_in() ) {
  redirect_to_url(wp_login_url());  
}
$user = wp_get_current_user();
$cb_subscription = apply_filters("cb_get_subscription", $user->ID);
if( !isset($cb_subscription) ) {
  return;
}
try {
  $return_url = site_url();
  $result = ChargeBee_PortalSession::create(array("redirectUrl" => $return_url,
                                                "customer" => array("id" =>get_current_user_id())));
  $account_url = $result->portalSession()->accessUrl;
  redirect_to_url($account_url);
} catch(ChargeBee_APIError $e) {
  echo '<div class="cb-flash"><span class="cb-text-failure"> Cannot load your account page </span></div>';
}
?>
