<?php 

$user_id = get_current_user_id();
$user_cb_plan = apply_filters("cb_get_user_plan", $user_id); 

if( !empty($current_plan_in[0]) && isset($user_cb_plan) && in_array($user_cb_plan, $current_plan_in) ) {
 echo $content;
}

if( !empty($current_plan_not_in[0]) && isset($user_cb_plan) && !in_array($user_cb_plan, $current_plan_not_in) ) {
 echo $content;
}

?>
