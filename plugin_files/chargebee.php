<?php
/**
 * @package ChargeBee
 * @version 1.0
 */
/*
Plugin Name: ChargeBee
Plugin URI: https://github.com/chargebee/chargebee-wordpress-plugin
Description: Manage Subscriptions From WordPress
Author: ChargeBee
Version: 1.0
Author URI: https://www.chargebee.com
*/

$base = dirname(__FILE__);
include($base.'/lib/ChargeBee.php');
require_once($base . "/util.php");
require_once($base . "/webhooks.php");
$SITE_URL=site_url();


register_activation_hook(__FILE__,array("chargebee_wp_plugin","install"));
register_deactivation_hook(__FILE__,array("chargebee_wp_plugin","uninstall"));
add_action('admin_menu', array("chargebee_wp_plugin","chargebee_admin_menu"));
add_action('admin_menu', array("chargebee_wp_plugin","chargebee_access_metadata"));
add_action('init', array("chargebee_wp_plugin", "configure_chargebee_env") );
add_action('user_register', array("chargebee_wp_plugin","chargebee_save_user_meta"), 10, 1 );
add_action('save_post', array("chargebee_wp_plugin","chargebee_metabox_save"));
add_filter("comments_array", array("chargebee_wp_plugin","check_for_comments"));
add_filter("comments_open", array("chargebee_wp_plugin","check_for_comments"));
add_filter('the_posts',array("chargebee_wp_plugin","chargebee_check_access"));


class chargebee_wp_plugin {

function install() {
    global $current_user;
    get_currentuserinfo();
    $AUTH_USER=$current_user->user_login;
    $SALT=uniqid(mt_rand(), true);
    $AUTH_PASSWD=sha1($SALT.$DOMAIN);
    $SITE_URL=site_url();

    $data = array(	'site_domain'=>'<your site name>',
			'api_key'=>'<your api key>',
			'webhook_user_auth'=>"$AUTH_USER",
			'webhook_user_pass'=>"$AUTH_PASSWD",
                        'not_logged_in_msg' => '<a href=wp-login.php?action=register>Register</a> to view this content',
                        'no_access_msg' => 'Content restricted to premium plans. Please upgrade to view this content.',
                        'cancel_msg' => 'Sorry you do not have access to this content. Please contact us for more information.'
	     );
    if(!get_option("chargebee")) {
	add_option("chargebee",$data);
    }
}


static function configure_chargebee_env() {
  $cboptions=get_option("chargebee");
  ChargeBee_Environment::configure($cboptions["site_domain"],$cboptions["api_key"]);
  if( isset($_GET) && isset($_GET["chargebee_webhook_call"]) && $_GET["chargebee_webhook_call"] == "true" ) {  
    do_action("handle_webhook");
    return;
  }
}


function uninstall() {
	delete_option("chargebee");
}

static function chargebee_admin_menu() {
	add_menu_page('ChargeBee Settings', 'ChargeBee', 'manage_options', 'plugin', 
                 array("chargebee_wp_plugin","chargebee_admin_page"), WP_PLUGIN_URL . "/chargebee/cb-fav.png" );
}

static function includeSettings($cboptions){
     require_once(dirname(__FILE__) . "/include/chargebee_settings.php");
}

static function chargebee_admin_page() {
	$cboptions=get_option("chargebee");
	$hidden_field_name="cb_hidden_field";

	if($_SERVER['REQUEST_METHOD'] == "GET") { 
            return chargebee_wp_plugin::includeSettings($cboptions);
        }

        if ( !isset( $_POST["nonce-cb-wordpress-action"] ) || 
                     !wp_verify_nonce($_POST["nonce-cb-wordpress-action"], "wp-action-cb-plugin" ) ) {
	        echo '<div class="error"><p><strong>Nonce did not match!</strong></p></div>';
                return chargebee_wp_plugin::includeSettings($cboptions);                
	} 

       
        $cboptions = $_POST['cb'];
        $cb_credential_params = array("site_domain","api_key",
                                      "webhook_user_auth", "webhook_user_pass");

        foreach($cb_credential_params as $cb_param) {
           if(!isset($cboptions[$cb_param]) || empty($cboptions[$cb_param]) ) {
	        echo '<div class="error"><p><strong>'. str_replace("_", " ",$cb_param) .' not present!</strong></p></div>';
                return chargebee_wp_plugin::includeSettings($cboptions);                
           }
        }                  
         
        update_option("chargebee",$cboptions);
        chargebee_wp_plugin::configure_chargebee_env();
        try {
               if( isset( $_POST["default_plan"]) && !empty($_POST["default_plan"]) ) {
	            $result = ChargeBee_Plan::retrieve($_POST["default_plan"]);
                    // updating default plan only if has trial period or price zero.
                    if( isset($result->Plan()->trialPeriod) || ($result->Plan()->price == 0) ) {
                       $cboptions["default_plan"] = $_POST["default_plan"]; 
                       update_option("chargebee",$cboptions);
                       echo '<div class="updated"><p><strong>Settings Updated</strong></p></div>';
                    } else {
                       echo '<div class="updated"><p><strong>Plan doesnt have trial period.</strong></p></div>';
                    }
               } else {
                   $result = ChargeBee_Plan::all();
                   echo '<div class="updated"><p><strong>Settings Updated. Default plan has not been updated</strong></p></div>';
               }
        } catch (ChargeBee_APIError $e) {
            $jsonError = $e->getJsonObject();
            if( isset($jsonError) ) {
               echo '<div class="error"><p><strong>'. $e->getMessage() .'</strong></p></div>';
            } else if( $jsonError['error_code'] == "api_authentication_invalid_key") {
               echo '<div class="error"><p><strong>API key Invalid</strong></p></div>';   
            } else if( $jsonError['error_code'] == "resource_not_found" ) { 
	       echo '<div class="error"><p><strong> Plan not found in ChargeBee </strong></p></div>';
            } else {  
               echo '<div class="error"><p><strong>'. $jsonError['error_msg'] .' </strong></p></div>';
            }
        }
        chargebee_wp_plugin::includeSettings($cboptions);
}



static function check_for_comments($comments,$post_id = NULL) {
     global $post;
     if($post_id == null) {
         $post_id = $post->ID;
     }

     if( !$comments ) {
         return $comments;
     }

     $user = wp_get_current_user();
     if(isset($user->roles[0]) && $user->roles[0] == 'administrator') {
         return $comments;
     }
 
     $cbplans = get_post_meta($post_id, 'cb_post_plans', true);
     if( isset($cbplans['plans']) ) {
        $plans = $cbplans['plans'];
        if( !is_user_logged_in() || ( isset($user->chargebee_plan) && !isset($plans[$user->chargebee_plan]) ) ) {  
           // if the post is not accessible then making comments also inaccessible
            $comments = false;
        }
     }

     return $comments;
}

static function chargebee_access_metadata(){
    add_meta_box( 'chargebee-meta-box', 'ChargeBee Access Control', 
                  array('chargebee_wp_plugin','chargebee_meta_box'), 'post', 'normal', 'high' );
    add_meta_box( 'chargebee-meta-box', 'ChargeBee Access Control', 
                  array('chargebee_wp_plugin','chargebee_meta_box'), 'page', 'normal', 'high' );
}

static function chargebee_meta_box() {
    $cboptions=get_option("chargebee");
    global $post;
    $post_plans = get_post_meta($post->ID, 'cb_post_plans', true);
    $plans = isset($post_plans["plans"])? $post_plans["plans"] : null;
    $nonce_value = wp_create_nonce( plugin_basename(__FILE__) );
    require_once(dirname(__FILE__) . "/include/chargebee_meta_box.php");

}


static function chargebee_save_user_meta($user_id) {
      $info = get_userdata( $user_id );
      $cboptions=get_option("chargebee");
      
      // if default plan is not set then not creating a subscription.
      if( !isset($cboptions["default_plan"])) {
         return;
      }
      try {
           // A subscription is created in ChargeBee with the default plan id.
           $result = ChargeBee_Subscription::create(array(
	                            "id" => $user_id,
			            "planId" => $cboptions["default_plan"], 
				     "customer" => array(
                                                   "email" => "$info->user_email", 
			                           "firstName" => "$info->first_name", 
				                   "lastName" => "$info->last_name"
				                   )
			             ));
            do_action('update_result', $result);
       } catch ( ChargeBee_APIError $e ) {
            // if any error is from ChargeBee setting the user plan to null.
          update_user_meta($user_id, 'chargebee_plan', null);
       }
}

static function chargebee_metabox_save($post_id) {
    
        if( empty($post_id) ) {
                return false;
        }
	if( !wp_verify_nonce( $_POST['access_noncename'], plugin_basename(__FILE__) ) ) {
		return $post_id;
	}

        // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
        // to do anything
        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) {
                return $post_id;
        }

	if( !empty($_POST['post_type']) && 'page' == $_POST['post_type'] ) {
	    if ( !current_user_can( 'edit_page', $post_id )) {
			return $post_id;
	    }
	} else {
	    if ( !current_user_can( 'edit_post', $post_id )) {
			return $post_id;
	    }
	}
       
        // updating the plans allowed for the post
	$cb_post_plans["plans"]=$_POST["cbplans"];
	update_post_meta($post_id, 'cb_post_plans', $cb_post_plans);
       
}

static function chargebee_check_access($posts) {
    $user = wp_get_current_user();
    if(isset($user->roles[0]) && $user->roles[0] == 'administrator') {
	return $posts;
    }
    $cboptions =get_option("chargebee");
    $cbsubscription = apply_filters("get_cb_subscription", $user->ID);
    
    foreach($posts as $k => $post) {
       $cbplans = get_post_meta($post->ID, 'cb_post_plans', true);
       if( isset($cbplans['plans']) && is_array($cbplans['plans']) ) {   // check posts has plan restriction
           $plans = $cbplans['plans'];
           if( !is_user_logged_in() ) {
                 $post->post_content = $cboptions["not_logged_in_msg"];
           } else if( isset($cbsubscription) && $cbsubscription->status == "cancelled" ) {
                  $post->post_content = $cboptions["cancel_msg"];
           } else if( !(isset($user->chargebee_plan) && $user->chargebee_plan != ""
                      && isset($plans[$user->chargebee_plan]) ) ) {  // check the user subscribed to any plan and if it matches with post plan
                  $post->post_content = $cboptions["no_access_msg"];   
	   }
        }
    }
    return $posts;
}

}
?>
