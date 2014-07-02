<?php

$base = dirname(__FILE__);
require_once($base.'/lib/ChargeBee.php');

add_action('cb_update_result', array("chargebee_meta","chargebee_update_from_result"), 10, 1);
add_action('cb_sync_user_meta', array("chargebee_meta","chargebee_sync_sub_and_cust"), 10, 1);

add_filter('cb_get_user_plan', array("chargebee_meta", "chargebee_user_plan"), 10, 1);
add_filter('cb_get_user_state', array("chargebee_meta", "chargebee_user_status"), 10, 1);
add_filter('cb_get_subscription', array("chargebee_meta", "chargebee_subscription_detail"), 10, 1);
add_filter('cb_get_customer', array("chargebee_meta", "chargebee_customer_detail"), 10, 1);


class chargebee_meta {

/*
 * Gets the ChargeBee Subscription object of the user_id passed, if present at the wp_uermeta table.
 */
static function chargebee_subscription_detail($userId) {
    global $cb_user_meta;
    if( isset($cb_user_meta["subscription"]) ) {
       return $cb_user_meta["subscription"];
    } 
    $cboptions = get_option("chargebee"); 
    $sub_site = get_user_meta($userId, "cb_site", true);
    if( $sub_site == $cboptions['site_domain'] ) {
      $cb_subscription = get_user_meta($userId, "subscription", true);
      $cb_user_meta["subscription"] = $cb_subscription;
    } else {
      $cb_user_meta["subscription"] = null;
    }
    return $cb_user_meta["subscription"];
}

/*
 * Gets the ChargeBee Customer object of the user_id passed, if present at the wp_usermeta table.
 */
static function chargebee_customer_detail($userId) {
    global $cb_user_meta;
    if( isset($cb_user_meta["customer"]) ) {
       return $cb_user_meta["customer"];
    }
    $cboptions = get_option("chargebee"); 
    $sub_site = get_user_meta($userId, "cb_site", true);
    if ( $sub_site == $cboptions['site_domain'] ) {
      $cb_customer = get_user_meta($userId, "customer", true);
      $cb_user_meta["customer"] = $cb_customer;
    } else {
      $cb_user_meta["customer"] = null;
    }
    return $cb_user_meta["customer"];
}

/*
 * Updates the subscription and customer object for the user
 * from the ChargeBee result object.
 * Here wordpress user_id, ChargeBee subscription id and ChargeBee customer id are same.
 */
static function chargebee_update_from_result($response) {
  global $cb_user_meta;
  if( $response->subscription() != null ) {
      $cb_subscription = $response->subscription(); 
      update_user_meta($cb_subscription->id, 'subscription', $cb_subscription);
      $cb_user_meta["subscription"] = $cb_subscription;
   }
 
   if( $response->customer() != null) {
      $cb_customer = $response->customer();
      update_user_meta($cb_customer->id, 'customer', $cb_customer);
      $cb_user_meta["customer"] = $cb_customer;
   }
}

/*
 * Get the user current plan in ChargeBee.
 * 
 */
static function chargebee_user_plan($user_id) {
 $cb_subscription = apply_filters("cb_get_subscription",$user_id);   
 if( !isset($cb_subscription) ) {
   return null;
 }
 return $cb_subscription->planId;
}

/*
 * Get the user subscription state in ChargeBee.
 */
static function chargebee_user_status($user_id) {
 $cb_subscription = apply_filters("cb_get_subscription",$user_id);   
 if( !isset($cb_subscription) ) {
  return null;
 }
 return $cb_subscription->status;
}

/* 
 * Do a sync of users' subscription and customer meta in WordPress from ChargeBee. 
 */
static function chargebee_sync_sub_and_cust($user_id) {
 if( $user_id == 0 ) {
   return;
 }
 $result = ChargeBee_Subscription::retrieve($user_id);
 do_action("cb_update_result", $result); 
}


}

?>
