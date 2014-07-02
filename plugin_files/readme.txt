=== ChargeBee ===
Contributors: ChargeBee
Tags: memberships, membership,subscription,recurring billing, ecommerce, paywall, restrict access, restrict content, authorize.net, paypal, stripe, braintree
Tested up to: 3.8.2 
Stable tag: 2.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The best recurring billing experience with paywall functionality to control accesss to webpage content on your WordPress site using ChargeBee's subscription billing platform.  

== Description ==

A plugin to manage memberships on your WordPress site with ChargeBee.

This plugin also helps publishers using WordPress to easily provide access/restrict content to subscribers using the plans configured in ChargeBee.

ChargeBee manages the entire subscription life-cycle starting from trial to paid to canceled and also freemium users. The plugin automatically updates changes that happen in ChargeBee to WordPress. 

It also connects your subscribers to ChargeBee's customer portal in case you want to provide them with the option to manage their account. This includes viewing past payments, updating card details and also canceling their subscriptions.

[youtube https://www.youtube.com/watch?v=ngVFPdmuBVw]

= What does the plugin do? =

* The plugin acts as a paywall in resticting content to your users based on their pricing plans.
* Provides the option to add customers to a default plan during signup.
* Changes that takes place to subscriptions in ChargeBee are updated in WordPress. These changes are updated using webhooks.
* Connects to ChargeBee's customer portal for your users to easily access and manage their account and payment information.

= Easy access to ChargeBee's features with URLs =

The plugin includes URLs that can be used for specific functions such as changing an existing user's subscription to another plan and also to connect users to ChargeBee's customer portal. All this can be done within your WordPress site.

= Access users' ChargeBee meta information using filters =

* apply_filter("cb_get_subscription", $user_id) - gives the user's subscription details present in ChargeBee. This will be in the [ChargeBee Subscription](https://apidocs.chargebee.com/docs/api/subscriptions?lang=php#subscription_attributes) object format.
* apply_filter("cb_get_customer", $user_id) - gives the user's customer details present in ChargeBee. This will be in the [ChargeBee Customer](https://apidocs.chargebee.com/docs/api/customers?lang=php#customer_attributes) object format.
* apply_filters("cb_get_user_plan", $user_id) - gives the current user's subscription plan ID in ChargeBee.

= Short Codes = 

The plugin supports short codes that can be embedded into posts or pages on your site. This can be used to display messages or content to users based on their current plan. It can also be used to create a custom pricing page.

= About ChargeBee =

[ChargeBee](https://www.chargebee.com/) is an easy to use online billing platform for subscription businesses. 

With ChargeBee you can:

* Manage trial and freemium subscriptions
* Automatically invoice your customers and collect payment
* Notify your customers during various stages of the customer life cycle using our email notifications
* Set up, run and manage different discount campaigns 
* Use your choice of payment gateway. ChargeBee suppports Stripe, PayPal Pro, Braintree, Authorize.net, eWay and much more
* Automatically prorate subscriptions and also handle credits and refunds
* Create and manage offline subscriptions
* Completely customizable hosted payment pages and customer portal
* Handle EU VAT and general taxes
* Comprehensive API support with multiple client libraries

Find out [more](https://www.chargebee.com/subscription-billing-saas-features.html).


== Installation ==

= Plugin Installation = 

1. Download the latest version of the plugin, unzip the file on your computer and then upload it to your WordPress Plugin directory(/wp-content/plugins directory).
2. In your WordPress admin console, activate ChargeBee in the "Plugins" section.

Once the ChargeBee plugin is activated, it will appear on the menu bar.

Note: You can also install the plugin directly through your WordPress admin console. Go to the "Plugins" section, select "Add New", search for "ChargeBee" and then activate it.

= Plugin Setup = 

[youtube https://www.youtube.com/watch?v=xJZJ2O89xXw]

1. A ChargeBee account. <a href="https://app.chargebee.com/signup" target="_blank">Signup</a> for a free trial if you don't have one.
2. Configure your ChargeBee site name, API key and default plan.
3. Copy the webhook and "Return & Cancel" URLs from WordPress and configure them in ChargeBee.

Save your changes to complete the setup.

You can restrict access to posts by specifying a plan for them, making it available only for users that are on the specified plan.

1. Go to "Posts" in WordPress and select the post you want to restrict access to.
2. Use the "ChargeBee Access Control" section located at the bottom of the post to specify which plans have access to that specific post.
3. Update your changes.

= Plan Upgrade Page =

[youtube https://www.youtube.com/watch?v=Gtz0Qf7N370] 

You can use the plugin's default plan listing page to upgrade to a specific plan to view the restricted content.
If users do not already have a valid credit card in ChargeBee, during upgrade they will directed to ChargeBee's hosted checkout page to complete the upgrade.


= Customer Portal Page = 

[youtube https://www.youtube.com/watch?v=krgvx1bPzvU]

ChargeBee's customer portal can also be linked to your WordPress site to allow users to view and update their account and payment information as well as view past invoices. 


== Frequently Asked Questions ==

= How do I get support? =

Chat with us <a target="_blank" href="https://www.hipchat.com/glHFgk0FI">here</a> or email us at support@chargebee.com.

= What are webhooks? How do I test them in a local environment? =

Webhooks are callback services that notifies WordPress about the subscription changes that happen in ChargeBee. Learn more about webhooks <a target="_blank" href="https://www.chargebee.com/docs/events_and_webhooks.html#webhooks">here</a>

You cannot test webhooks locally. If you want to try it out locally. you should use a local tunneling tool like <a target="_blank" href="https://ngrok.com/">ngrok</a>.

= Can I securely collect credit card information? =

The plugin connects your WordPress site with the <a target="_blank" href="https://app.chargebee.com">ChargeBee app</a> and uses ChargeBee's hosted checkout page for sign ups. Since the card details are collected through the hosted pages, you do not handle any sensitive card information, reducing your PCI compliance requirements.

= Are the hosted checkout pages customizable? =

Absolutely! Our hosted pages support themes and are customizable. You can easily make the hosted pages match your website's look and branding. Learn more <a target="_blank" href="https://www.chargebee.com/docs/themes.html">here</a>.


= Does the plugin include a customer portal? =

Yes. ChargeBee has its own customer portal that allows your site users to view and manage their subscription information.

= ChargeBee has many APIs. How do I invoke them in WordPress? =

In order to invoke other APIs, just call the required API code from within WordPress. Initializing the environment for calling ChargeBee's APIs will be taken care by the plugin. After calling the API ensure that you update the user meta information of ChargeBee in WordPress. The "do_action('cb_update_result', $result)" takes the response received during the API call as input and updates the ChargeBee user meta information in WordPress.

== Screenshots ==

1. Configure the basic plugin configuration here including your ChargeBee site name, API key and a default plan(trial or freemium plan).
2. Specify the plans subscribers need to be on to get access to specific posts.
3. When a specific content is restricted, this is what users will see.
4. ChargeBee's default plan listing page. This is automatically generated during plugin installation. If you have your own plan page, you can use that as well.
5. ChargeBee's hosted checkout page is used to get customer's card details during plan change. Using the hosted checkout pages reduces your PCI compliance requirements. The hosted pages are responsive and you can also customize it to match the look and feel of your website.
6. ChargeBee's customer portal page can be accessed through your WordPress site. Copy the customer portal URL available in the plugin's settings and add it to your site as a menu or a link.
7. Define your products in ChargeBee by configuring your plans, addons and coupons. Plans configured in ChargeBee will automatically be listed in the plan listing page generated by the plugin.
8. All the signups that happen through your WordPress site are listed as subscriptions in ChargeBee. You can view all the subscription details including invoices and transactions and also make changes to the subscriptions if needed. 


== Changelog ==

= 2.2 =
Updating ChargeBee user meta in WordPress after redirecting from customer portal, removed "chargebee plan" property, if invoice name is null then replace it will plan name and also added filters for developers to access the ChargeBee user meta information.

= 2.1 =
Short code had been added to show custom messages based on the user plan

= 2.0 =
Option for customer to view the available plans and to switch their plan by themselves. Customers can use the ChargeBee hosted page and customer portal feature.

= 1.1 =
Webhook calls are checked with the existing customer subscription information and if any change found they are fetched from the ChargeBee.

= 1.0 =
Initial version of ChargeBee plugin.


== Upgrade Notice ==

= 2.2 =
Updating ChargeBee user meta in WordPress after redirecting from customer portal, removed "chargebee plan" property, if invoice name is null then replace it will plan name and also added filters for developers to access the ChargeBee user meta information.

= 2.1 =
Short code had been added to show custom messages based on the user plan
 
= 2.0 =
Option for customer to view the available plans and to switch their plan by themselves. Customers can use the ChargeBee hosted page and customer portal feature.

= 1.1 =
If the webook data is different from wordpress subscription and customer meta then Subscription or Customer retrieve API call is performed and then meta information are updated.

= 1.0 =
This is the initial base version of ChargeBee plugin.


