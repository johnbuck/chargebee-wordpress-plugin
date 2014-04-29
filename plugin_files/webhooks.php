<?php

$base = dirname(__FILE__);
require_once($base . "/../../../wp-load.php");
require_once($base . "/util.php");
$cboptions=get_option("chargebee");

$CB_API_KEY= $cboptions["api_key"];
$CB_DOMAIN_NAME= $cboptions["site_domain"];
$CB_PHP_AUTH_USER = $cboptions["webhook_user_auth"];
$CB_PHP_AUTH_PW = $cboptions["webhook_user_pass"];


global $wpdb;
$username = null;
$password = null;

if( isset($_SERVER['PHP_AUTH_USER'] ) ) {
  $username = $_SERVER['PHP_AUTH_USER'];
  $password = $_SERVER['PHP_AUTH_PW'];
}

if (is_null($username) || !($username==$CB_PHP_AUTH_USER && $password == $CB_PHP_AUTH_PW) ) {
    header('HTTP/1.0 401 Unauthorized');
    echo "401 Unauthorized";
    die();
} else {
    $content = file_get_contents('php://input'); 
    $webhook_content = ChargeBee_Event::deserialize($content); 
    checkSubscriptionCustomerId($webhook_content->content());
    do_action("update_result", $webhook_content->content());
}


function checkSubscriptionCustomerId($content) {
 if( $content->customer() != null) {
   check404($content->customer()->id);
 }
 if( $content->subscription() != null ) {
   check404($content->subscription()->id);
 }

}


function check404($user_id) {
 if( !get_userdata( $user_id ) ) {
     header('HTTP/1.0 404 Id not found '.$user_id);
     echo $user_id . " Id in the webhook is not found in wordpress";
     die();
 }
}
?>
