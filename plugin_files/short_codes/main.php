<?php

add_shortcode('cb_plan_list','listing_plans');
add_shortcode('cb_checkout', 'checkout');
add_shortcode('cb_redirect_handler','hosted_page_redirect_handler');

add_shortcode('cb_account','customer_portal');

/*
 * Plan listing short code
 */
function listing_plans($atts) {
       include(dirname(__FILE__). "/list_plan.php");
}

/*
 * Short code takes to ChargeBee Checkout page for the specified plan id.
 */
function checkout($atts, $cb_plan_id = null ) {
        include(dirname(__FILE__) . "/checkout.php");
}

/*
 * Short code that handles the return url after checkout
 * The return url page should have this short code to update the user accordingly.
 */
function hosted_page_redirect_handler($atts) {
     include(dirname(__FILE__) . "/redirect_handler.php");
}

/*
 * Short codes that redirects the customer to the ChargeBee Single Sign on Customer Portal page. 
 */
function customer_portal($atts) {
    include(dirname(__FILE__) . "/account.php");
}

?>
