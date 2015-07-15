<?php
/**
 * @package ChargeBee
 * @version 2.4.2
 */
/*
 Plugin Name: ChargeBee
 Plugin URI: https://github.com/chargebee/chargebee-wordpress-plugin
 Description: Manage Subscriptions From WordPress
 Author: ChargeBee
 Version: 2.4.2
 Author URI: https://www.chargebee.com
*/

$base = dirname(__FILE__);
include($base.'/lib/ChargeBee.php');
require_once($base . "/cb_wp_functions.php");
require_once($base . "/util_functions.php");
require_once($base . "/webhooks.php");
require_once($base . "/short_codes/main.php");

$SITE_URL=site_url();

register_activation_hook(__FILE__,array("chargebee_wp_plugin","install"));

add_action('init', array("chargebee_wp_plugin", "init_chargebee") );

add_action('user_register', array("chargebee_wp_plugin","chargebee_save_user_meta"), 10, 1 );

add_action('admin_menu', array("chargebee_wp_plugin","chargebee_admin_menu"));
add_action('admin_menu', array("chargebee_wp_plugin","chargebee_access_metadata"));

add_action('save_post', array("chargebee_wp_plugin","chargebee_metabox_save"));
add_filter('the_posts',array("chargebee_wp_plugin","chargebee_check_access"));

add_action('wp_enqueue_scripts', array("chargebee_wp_plugin","chargebee_css"));
add_action('admin_enqueue_scripts', array("chargebee_wp_plugin","chargebee_admin_assets"));

add_filter("comments_array", array("chargebee_wp_plugin","check_for_comments"));
add_filter("comments_open", array("chargebee_wp_plugin","check_for_comments"));

class chargebee_wp_plugin {

static function install() {
    global $current_user;
    get_currentuserinfo();

    if(!get_option("chargebee"))  {
         $AUTH_USER=$current_user->user_login;
         if( strpos($AUTH_USER, "@") ) {
          $AUTH_USER = substr($AUTH_USER, 0, strpos($AUTH_USER,"@")) ;
         }
         $SALT=uniqid(mt_rand(), true);
         $AUTH_PASSWD=sha1($SALT.$DOMAIN);
         $SITE_URL=site_url();

         $cboptions = array(); 
         $plan_page = array( 'post_title' => "Plan Pricing",
                            'post_status' => 'publish',
                            'post_type' => 'page',
                            'post_content' => '[cb_plan_list]',
                            'comment_status' => 'closed',
                            'ping_status' => 'closed' );

         $plan_page_id = wp_insert_post($plan_page); 
         $cboptions = array(	'webhook_user_auth'=>"$AUTH_USER",
			        'webhook_user_pass'=>"$AUTH_PASSWD",
                                'plan_page' => $plan_page_id,
                                'plan_page_generated' => "true",
                                'currency' => "$",
                                'not_logged_in_msg' => '<a href="wp-login.php?action=register">Register</a> to view this content',
                                'no_access_msg' => 'Content restricted to premium plans. Please <a href="'.$SITE_URL.'?chargebee_plan_page=true">upgrade</a> to view this content.',
                                'cancel_msg' => 'Sorry you do not have access to this content. Please check your subscription status <a href="' . $SITE_URL . '?chargebee_portal=true"> here </a>.', 
                                'checkout_success_msg' => '<div class="cb-flash"><span class="cb-text-success">Plan Changed Successfully</span></div>',
                                'checkout_failure_msg' => '<div class="cb-flash"><span class="cb-text-failure">Plan Change Aborted</span></div>' 
                           );
        add_option("chargebee",$cboptions);
    } 
}


static function init_chargebee() {
  global $cboptions, $cb_user_meta;
  $cboptions = get_option("chargebee");
  $user = wp_get_current_user();
 
  if( isset($cboptions["site_domain"]) && isset($cboptions["api_key"])) { 
     ChargeBee_Environment::configure($cboptions["site_domain"],$cboptions["api_key"]);
  }
  $cb_subscriptions = apply_filters("cb_get_subscription", $user->ID);
  $cb_customers = apply_filters("cb_get_customer", $user->ID );

  if( isset($_GET) ) {
      if ( isset($_GET["chargebee_webhook_call"]) && $_GET["chargebee_webhook_call"] == "true" ) {  
         do_action("handle_webhook");
         return;
      } else if ( isset($_GET["chargebee_checkout_redirection"]) && $_GET["chargebee_checkout_redirection"] == "true" ) {
         cb_hosted_page_redirect_handler(null);
         return;
      } else if ( isset($_GET["chargebee_plan_id"]) && !empty($_GET["chargebee_plan_id"]) ) { 
         if( isset($cb_subscriptions) && isset($cb_customers) && isset($cboptions['change_plan']) && $cboptions['change_plan'] == "via_customer_portal") {
           cb_customer_portal(null); 
         } else {
          cb_checkout(array("cb_plan_id" => $_GET["chargebee_plan_id"])); 
         }
         return; 
      } else if ( isset($_GET["chargebee_portal"]) && $_GET["chargebee_portal"] == "true" ) {
         cb_customer_portal(null); 
         return;
      } else if ( isset($_GET["chargebee_plan_page"]) && $_GET["chargebee_plan_page"] == "true" ) {
         if( !(isset($cboptions["plan_page"]) || empty($cboptions["plan_page"])) ) {
           echo "Plan page not configured. Contact site admin to either change the upgrade url or configure the page";
           exit;
         } else { 
           $url = get_permalink($cboptions["plan_page"]);
           redirect_to_url($url);
           return;
         }
      } else if ( isset($_GET['chargebee_checkout_state']) && !empty($_GET['chargebee_checkout_state']) ) {
         if( $_GET['chargebee_checkout_state'] == "checkout_success" ) {
           echo $cboptions["checkout_success_msg"];
         } else if ($_GET['chargebee_checkout_state'] == "checkout_cancelled" ) {
           echo $cboptions["checkout_failure_msg"] ;
         }
      } else if ( isset($_GET['chargebee_portal_redirection']) && $_GET['chargebee_portal_redirection'] == "true" ) {
         do_action("cb_sync_user_meta", $user->ID); 
      } 
  }
}


static function chargebee_css() {
     wp_enqueue_style("cb-wp", plugins_url("css/chargebee.css", __FILE__));
}

static function chargebee_admin_assets() {
    wp_enqueue_script('cb_wp_admin',plugins_url("js/chargebee_admin.js" ,__FILE__), array('jquery')); 
}

static function chargebee_admin_menu() {
	add_menu_page('ChargeBee', 'ChargeBee', 'manage_options', 'chargebee.php', 
                           array("chargebee_wp_plugin","chargebee_admin_page"), WP_PLUGIN_URL . "/chargebee/cb-fav.png" );
	add_submenu_page('chargebee.php', 'Site Settings', 'Site Settings', 'manage_options', 'chargebee.php', 
                           array("chargebee_wp_plugin","chargebee_admin_page") );
        add_submenu_page('chargebee.php', 'Page Settings & Display Messages', 'Page Settings & Display Messages','manage_options', 
                          'cb_page_settings', array("chargebee_wp_plugin","chargebee_page_menu") );
        add_submenu_page('chargebee.php', 'Plugin URLs & Short Codes', 'Plugin URLs & Short Codes','manage_options', 
                          'cb_short_codes', array("chargebee_wp_plugin","chargebee_urls") );
        add_submenu_page('chargebee.php', 'Other Plugin Integrations', 'Other Plugin Integrations','manage_options', 
                          'cb_other_plugin_integrations', array("chargebee_wp_plugin","chargebee_user_registration") );

}

static function includeSiteSettings($cboptions) {
     require_once(dirname(__FILE__) . "/include/chargebee_site_settings.php");
}

static function includePageSettings($cboptions) {
     require_once(dirname(__FILE__) . "/include/chargebee_page_settings.php");
}

static function chargebee_urls() {
     require_once(dirname(__FILE__) . "/include/chargebee_urls.php");
}

static function chargebee_user_registration() {
  global $cboptions;
  if($_SERVER['REQUEST_METHOD'] == "GET") {
    return require_once(dirname(__FILE__) . "/include/chargebee_registration_settings.php");
  }
  if ( !isset( $_POST["nonce-cb-wordpress-action"] ) ||
                     !wp_verify_nonce($_POST["nonce-cb-wordpress-action"], "wp-action-cb-plugin-page-setting" ) ) {
      echo '<div class="error"><p><strong>Nonce did not match!</strong></p></div>';
      return require_once(dirname(__FILE__) . "/include/chargebee_registration_settings.php");
  }
  $cb_reg_req = $_POST['cb'];
  $msg = null;
  if( isset($cb_reg_req['store_register_param']) && $cb_reg_req['store_register_param'] == "true" ) {
    $msg = "From now the user registration fields will be recorded. Please do a sample registration and then stop recording registration field.";
  } else { 
    $msg = "Stopped capturing the user registration fields. Update your JSON accordingly from the last recorded registration fields.";
  }

  if( isset($cb_reg_req['cf_param_map']) ) {
    $cb_reg_req = stripslashes_deep($_POST['cb']);
    if( !is_null($cb_reg_req['cf_param_map']) && !empty($cb_reg_req['cf_param_map']) && json_decode($cb_reg_req['cf_param_map']) == null) {
        echo '<div class="error"><p><strong>Invalid mapping JSON</strong></p></div>';
        return require_once(dirname(__FILE__) . "/include/chargebee_registration_settings.php");
    }
    $cb_reg_req['cf_param_map'] = json_decode($cb_reg_req['cf_param_map']);
    $msg = "Updated mapping JSON";
  }

  $cboptions = array_merge($cboptions, $cb_reg_req); 
  update_option("chargebee", $cboptions);
  echo '<div class="updated"><p><strong>'. $msg. '</strong></p></div>';
  return require_once(dirname(__FILE__) . "/include/chargebee_registration_settings.php");
}


static function chargebee_page_menu() {
    global $cboptions; 
    if($_SERVER['REQUEST_METHOD'] == "GET") {
      return chargebee_wp_plugin::includePageSettings($cboptions);
    }
    if ( !isset( $_POST["nonce-cb-wordpress-action"] ) ||
                     !wp_verify_nonce($_POST["nonce-cb-wordpress-action"], "wp-action-cb-plugin-page-setting" ) ) {
        echo '<div class="error"><p><strong>Nonce did not match!</strong></p></div>';
        return chargebee_wp_plugin::includePageSettings($cboptions);
    }

    $cbpage_settings = stripslashes_deep($_POST['cb']);

    $cboptions = array_merge($cboptions, $cbpage_settings);

    update_option("chargebee",$cboptions);
    echo "<div class='updated'> Settings updated </div>";
    chargebee_wp_plugin::includePageSettings($cboptions); 
}

static function chargebee_admin_page() {
	global $cboptions; 

	if($_SERVER['REQUEST_METHOD'] == "GET") { 
            return chargebee_wp_plugin::includeSiteSettings($cboptions);
        }

        if ( !isset( $_POST["nonce-cb-wordpress-action"] ) || 
                     !wp_verify_nonce($_POST["nonce-cb-wordpress-action"], "wp-action-cb-plugin-site-setting" ) ) {
	        echo '<div class="error"><p><strong>Nonce did not match!</strong></p></div>';
                return chargebee_wp_plugin::includeSiteSettings($cboptions);                
	} 

       
        $cbsite_details = $_POST['cb'];
        $cb_credential_params = array("site_domain","api_key", 
                                      "webhook_user_auth", "webhook_user_pass");

        foreach($cb_credential_params as $cb_param) {
           if(!isset($cbsite_details[$cb_param]) || empty($cbsite_details[$cb_param]) ) {
	        echo '<div class="error"><p><strong>'. str_replace("_", " ",$cb_param) .' not present!</strong></p></div>';
                return chargebee_wp_plugin::includeSiteSettings($cbsite_details);                
           }
        }                  
        $cboptions = array_merge($cboptions, $cbsite_details); 
        $cboptions['default_plan'] = ""; 
        update_option("chargebee",$cboptions);
        chargebee_wp_plugin::init_chargebee();
        try {
               if( isset( $_POST["default_plan"]) && !empty($_POST["default_plan"]) ) {
	            $result = ChargeBee_Plan::retrieve($_POST["default_plan"]);
                    // updating default plan only if it has trial period or plan price zero.
                    if( isset($result->Plan()->trialPeriod) || ($result->Plan()->price == 0) ) {
                       $cboptions["default_plan"] = $_POST["default_plan"]; 
                       update_option("chargebee",$cboptions);
                       echo '<div class="updated"><p><strong>Settings Updated</strong></p></div>';
                    } else {
                       echo '<div class="error"><p><strong>Only Trial or Zero dollar plans are allowed.</strong></p></div>';
                    }
               } else {
                   $result = ChargeBee_Plan::all();
                   echo '<div class="updated"><p><strong>Settings Updated. Default plan has not been updated</strong></p></div>';
               }
        } catch (ChargeBee_APIError $e) {
            $jsonError = $e->getJsonObject();
            if( !isset($jsonError) ) {
               echo '<div class="error"><p><strong>'. $e->getMessage() .'</strong></p></div>';
            } else if( $jsonError['error_code'] == "api_authentication_invalid_key") {
               echo '<div class="error"><p><strong>API key Invalid</strong></p></div>';   
            } else if( $jsonError['error_code'] == "resource_not_found" ) { 
	       echo '<div class="error"><p><strong> Plan not found in ChargeBee </strong></p></div>';
            } else {  
               echo '<div class="error"><p><strong>'. $jsonError['error_msg'] .' </strong></p></div>';
            }
        }
        chargebee_wp_plugin::includeSiteSettings($cboptions);
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
        $cb_current_plan = apply_filters("cb_get_user_plan",$user->ID);
        if( !is_user_logged_in() || ( isset($cb_current_plan) && !isset($plans[$cb_current_plan]) ) ) {  
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
    global $post, $cboptions; 
    $post_plans = get_post_meta($post->ID, 'cb_post_plans', true);
    $plans = isset($post_plans["plans"])? $post_plans["plans"] : null;
    $nonce_value = wp_create_nonce( plugin_basename(__FILE__) );
    require_once(dirname(__FILE__) . "/include/chargebee_meta_box.php");

}


static function chargebee_save_user_meta($user_id) {
      $info = get_userdata( $user_id );
      $cboptions = get_option("chargebee");
      if( isset($cboptions['store_register_param']) && $cboptions['store_register_param'] == "true" ) {       
        update_option("cb_user_register_param", $_POST); 
      }
  
      // if default plan is not set then subscription will not be created when registering in WordPress.
      if( !isset($cboptions["default_plan"])) {
         return;
      }
      // adding custom fields from the request
      $customer = array( "email" => "$info->user_email", 
                         "firstName" => "$info->first_name", 
                         "lastName" => "$info->last_name"
                        );
      if( isset($cboptions['cf_param_map']) ) {
          foreach($cboptions['cf_param_map'] as $cf => $params) {
             $req = $_POST;
             $fields = explode(".",$params);
             foreach($fields as $f) {
               $req = $req[$f];
             }
             if( is_array($req) ) { 
               if((bool)count(array_filter(array_keys($req), 'is_string')) ) { 
                 $customer[$cf] = json_encode($req);
               } else {
                 $customer[$cf] = implode(",", $req);
               } 
             } else {
               $customer[$cf] = json_encode($req);
             }
          }       
      }

      try {
           // A subscription is created in ChargeBee with the default plan id.
           $result = ChargeBee_Subscription::create(array(
	                            "id" => $user_id,
			            "planId" => $cboptions["default_plan"], 
				    "customer" => $customer 
			             ));
            do_action('cb_update_result', $result);
            update_user_meta($user_id, "cb_site", $cboptions['site_domain'] );
       } catch ( ChargeBee_APIError $e ) {
         $jsonError = $e->getJsonObject(); 
         echo $jsonError["error_msg"];
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
    global $cboptions;
    $user = wp_get_current_user();
    if(isset($user->roles[0]) && $user->roles[0] == 'administrator') {
	return $posts;
    }

    $allowedChars = $cboptions["allowed_chars"];
    $cbsubscription = apply_filters("cb_get_subscription", $user->ID);
    $cb_current_plan = apply_filters("cb_get_user_plan", $user->ID);

    foreach($posts as $k => $post) {
       $cbplans = get_post_meta($post->ID, 'cb_post_plans', true);
       if( isset($cbplans['plans']) && is_array($cbplans['plans']) ) {   // check posts has plan restriction
           $plans = $cbplans['plans'];
           if( !is_user_logged_in() ) {
                 $post->post_content = substr($post->post_content,0,$allowedChars) . $cboptions["not_logged_in_msg"];
           } else if( !(isset($cbsubscription)) ) {  // check chargebee subscription object in wp.
                 $post->post_content =  substr($post->post_content,0,$allowedChars) . $cboptions["no_access_msg"];
           } else if( $cbsubscription->status == "cancelled" ) {  //check the subscription state in chargebee 
                  $post->post_content = $cboptions["cancel_msg"];
           } else if( !(isset($cb_current_plan) && isset($plans[$cb_current_plan])) ) {  // check the user has subscribed to any plan and if it matches with post plan
                  $post->post_content =  substr($post->post_content,0,$allowedChars) .  $cboptions["no_access_msg"];   
	   }
        }
    }
    return $posts;
}


}

?>
