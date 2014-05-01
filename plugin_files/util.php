<?php

$base = dirname(__FILE__);
require_once($base.'/lib/ChargeBee.php');

add_action('update_result',array("chargebee_meta","update_from_result"),10,1);

add_filter('get_cb_subscription', array("chargebee_meta", "chargebee_subscription_detail"), 10,1);
add_filter('get_cb_customer', array("chargebee_meta", "chargebee_customer_detail"), 10,1);

class chargebee_meta {

/*
 * Gets the ChargeBee Subscription object of the user_id passed, if present at the wp_uermeta.
 */
function chargebee_subscription_detail($userId) {
    return  get_user_meta($userId, "subscription", true);
}

/*
 * Gets the ChargeBee Customer object of the user_id passed, if present at the wp_usermeta.
 */
function chargebee_customer_detail($userId) {
    return get_user_meta($userId, "customer", true);
}

/*
 * Updates the subscription and customer object for the user
 * from the ChargeBee result object.
 * Here wordpress user_id, ChargeBee subscription id and ChargeBee customer id are same.
 */
function update_from_result($response) {
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
