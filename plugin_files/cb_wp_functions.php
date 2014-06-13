<?php

$base = dirname(__FILE__);
require_once($base.'/lib/ChargeBee.php');

add_action('cb_update_result',array("chargebee_meta","update_from_result"), 10, 1);

add_filter('cb_get_subscription', array("chargebee_meta", "chargebee_subscription_detail"), 10, 1);
add_filter('cb_get_customer', array("chargebee_meta", "chargebee_customer_detail"), 10, 1);


class chargebee_meta {

/*
 * Gets the ChargeBee Subscription object of the user_id passed, if present at the wp_uermeta table.
 */
static function chargebee_subscription_detail($userId) {
    $cboptions = get_option("chargebee"); 
    $sub_site = get_user_meta($userId, "cb_site", true);
    if ( $sub_site == $cboptions['site_domain'] ) {
      return  get_user_meta($userId, "subscription", true);  
    } else {
      return null;
    }
}

/*
 * Gets the ChargeBee Customer object of the user_id passed, if present at the wp_usermeta table.
 */
static function chargebee_customer_detail($userId) {
    $cboptions = get_option("chargebee"); 
    $sub_site = get_user_meta($userId, "cb_site", true);
    if ( $sub_site == $cboptions['site_domain'] ) {
      return get_user_meta($userId, "customer", true);
    } else {
      return null;
    }
}

/*
 * Updates the subscription and customer object for the user
 * from the ChargeBee result object.
 * Here wordpress user_id, ChargeBee subscription id and ChargeBee customer id are same.
 */
static function update_from_result($response) {
  if( $response->subscription() != null ) {
      $subscription = $response->subscription(); 
      update_user_meta($subscription->id, 'subscription', $subscription);
      // Updating the plan for the user.
      update_user_meta($subscription->id, 'chargebee_plan', $subscription->planId);
   }
 
   if( $response->customer() != null) {
      $customer = $response->customer();
      update_user_meta($customer->id, 'customer', $customer);
   }

}


}

?>
