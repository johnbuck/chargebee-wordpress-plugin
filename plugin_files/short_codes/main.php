<?php

add_shortcode('cb_plan_list','cb_listing_plans');
add_shortcode('cb_checkout', 'cb_checkout');
add_shortcode('cb_decision','cb_check_for_plans');
add_shortcode('cb_account','cb_customer_portal');

add_shortcode('cb_redirect_handler','cb_hosted_page_redirect_handler');

/*
 * Plan listing short code
 */
function cb_listing_plans($atts) {
       include(dirname(__FILE__). "/list_plan.php");
}

/*
 * Short code takes to ChargeBee Checkout page for the specified plan id.
 */
function cb_checkout($atts) {
        $cb_plan_id = $atts["cb_plan_id"]; 
        include(dirname(__FILE__) . "/checkout.php");
}

/*
 * Short code that handles the return url after checkout
 * The return url page should have this short code to update the user accordingly.
 */
function cb_hosted_page_redirect_handler($atts) {
     include(dirname(__FILE__) . "/redirect_handler.php");
}


/*
 * Short codes that redirects the customer to the ChargeBee Single Sign on Customer Portal page. 
 */
function cb_customer_portal($atts) {
    include(dirname(__FILE__) . "/account.php");
}

function cb_check_for_plans($atts, $content = null) {
    $current_plan_in = explode(",", $atts["current_plan_in"]);
    $current_plan_not_in = explode(",", $atts["current_plan_not_in"]);
    include(dirname(__FILE__) . "/check_plan.php"); 
}

?>
