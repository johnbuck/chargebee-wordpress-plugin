<?php 

$user_id = get_current_user_id();
$user_cb_plan = get_user_meta($user_id,"chargebee_plan",true);

if( isset($user_cb_plan) && in_array($user_cb_plan, $current_plan_in) ) {
 echo $content;
}

if( isset($user_cb_plan) && !in_array($user_cb_plan, $current_plan_not_in) ) {
 echo $content;
}

?>
