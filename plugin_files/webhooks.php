<?php

add_action('handle_webhook', array("chargebee_webhook", "process_webhook"), 1, 1);

class chargebee_webhook {

  static function process_webhook() {
      $cboptions = get_option("chargebee");
      $cb_api_key= $cboptions["api_key"];
      $cb_domain_name = $cboptions["site_domain"];
      $cb_php_auth_user = $cboptions["webhook_user_auth"];
      $cb_php_auth_pw = $cboptions["webhook_user_pass"];

      $username = null;
      $password = null;

      if( isset($_SERVER['PHP_AUTH_USER'] ) ) {
           $username = $_SERVER['PHP_AUTH_USER'];
           $password = $_SERVER['PHP_AUTH_PW'];
      }
      try {
         if (is_null($username) || !($username==$cb_php_auth_user && $password == $cb_php_auth_pw) ) {
            header('HTTP/1.0 401 Unauthorized');
            echo "401 Unauthorized";
         } else {
            $content = file_get_contents('php://input'); 
            $webhook_content = ChargeBee_Event::deserialize($content); 
            chargebee_webhook::check_chargebee_id_present( $webhook_content->content());
            echo "Webhook from ChargeBee processed";
         }
      } catch( Exception $e) {
         header('HTTP/1.0 500 Internal Server error');
         echo "Message :" . $e->getMessage();
         echo "\nError in processing webhook";
      }
      die();
   }
   
   static function check_chargebee_id_present($content) {
      $fetch = false;
      if( $content->customer() != null) {
          chargebee_webhook::check_userid_in_wp($content->customer()->id);
          $customer = apply_filters("cb_get_customer", $content->customer()->id);
          if( $customer != $content->customer() ) {
            $fetch = true;
          }
      }
      if( $content->subscription() != null ) {
          do_action("check_userid_wp",$content->subscription()->id);
          $subscription = apply_filters("cb_get_subscription", $content->subscription()->id);
          if( $subscription != $content->subscription() ) {
            $fetch = true;
          }
      }

      if($fetch) {
         $result = ChargeBee_Subscription::retrieve($content->subscription()->id);
         update_user_meta($result->customer()->id, 'subscription', $result->subscription());
         update_user_meta($result->customer()->id, 'customer', $result->customer());
      }
   }


   static function check_userid_in_wp($user_id) {
      if( !get_userdata( $user_id ) ) {
         header('HTTP/1.0 404 Id not found '.$user_id);
         echo "Id " . $user_id . " in the webhook is not found in wordpress";
         die();
       }
   }

}
?>
