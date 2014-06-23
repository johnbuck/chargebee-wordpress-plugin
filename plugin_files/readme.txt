=== ChargeBee ===
Contributors: ChargeBee
Tags: memberships, membership,subscription,recurring billing, ecommerce, paywall, restrict access, restrict content, authorize.net, paypal, stripe, braintree
Tested up to: 3.8.2 
Stable tag: 2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The best recurring billing experience & membership specific content paywall on your wordpress site using ChargeBee Subscription Billing Platform.  

== Description ==

ChargeBee Plugin For membership management in subscription businesses.

[ChargeBee](https://www.chargebee.com/) is easy to use online billing platform for businesses using the paid membership model. With the ChargeBee wordpress Plugin, you will not only be able to manage all your subscriptions but also restrict content based on the plan the member has subscribed to.

[youtube https://www.youtube.com/watch?v=ngVFPdmuBVw]

= ChargeBee Feature Highlights =
ChargeBee is your one shop stop for managing your subscriptions. You will be able to:

* Provide trial period or you can choose to use a freemium model
* Automatically invoice your customers and collect payment
* Notify your customers during various stages of the customer life cycle, through our email notifications
* Add one time charges
* Set up, run and manage different discount campaigns
* Handle credits 
* Use your choice  of payment gateway. ChargeBee suppports Stripe, PayPal Pro, Braintree, Authorize.net eWay, and many more
* Suitable for merchants based out of USA, Canada, Australia, Great Britain, Singapore and 48+ more countries 
* An intuitive system that handles proration when a customer changes plans
* Offline Payments Tracking
* Metered Billing
* Completely customizable Payment Pages to look exactly like your gorgeous website
* Data Portability
* Reports and Dashboards
* Manage Shipping Address
* EU VAT and General Tax Support
* Comprehensive API support. Easy to use client libraries for Python, Ruby, JAVA, PHP and .NET
* Free Tech and Setup Support

And much [more](https://www.chargebee.com/subscription-billing-saas-features.html).


= ChargeBee Plugin provides the following functionality out of the box =

* When a user registers in wordpress a subscription is automatically created in ChargeBee with a default plan. You can control this behaviour.
* You will be able to share content with your customers based on their specific subscribed plan. The wordpress user id is used as the identifier with ChargeBee for user access.
* The plugin keeps the subscriber & customer information in sync with ChargeBee using ChargeBee webhook functionality. More on this in admin section. 


= Additional Functionality =
If you need additional functionality, you could build it easily on top of this plugin using ChargeBee's php library that is included in this plugin. Please view the [apidocs](https://apidocs.chargebee.com/docs/api) for reference. 

**Note:** The api key initialization is automatically handled.


== Installation ==

= Plugin Installation = 

1. Download the latest version of the plugin, unzip the file on your computer and then upload it to your WordPress Plugin directory(/wp-content/plugins directory).
2. In your WordPress admin console, activate ChargeBee in the "Plugins" section.

Once the ChargeBee plugin is activated, it will appear on the menu bar.

Note: You can also install the plugin directly through your WordPress admin console. Go to the "Plugins" section, select "Add New", search for "ChargeBee" and then activate it.

= Plugin Setup = 

1. Configure your ChargeBee site name, API key and default plan.
2. Copy the webhook and "Return & Cancel" URLs from WordPress and configure them in ChargeBee.

Save your changes to complete the setup.

You can restrict access to posts by specifying a plan for them, making it available only for users that are on the specified plan.

1. Go to "Posts" in WordPress and select the post you want to restrict access to.
2. Use the "ChargeBee Access Control" section located at the bottom of the post to specify which plans have access to that specific post.
3. Update your changes.

Checkout this video for a walk through on the initial setup
[youtube https://www.youtube.com/watch?v=xJZJ2O89xXw]

= Plan Upgrade Page =
You can use the plugin's default plan listing page to upgrade to a specific plan to view the restricted content.
If users do not already have a valid credit card in ChargeBee, during upgrade they will directed to ChargeBee's hosted checkout page to complete the upgrade.

Checkout this video on how customer can upgrade their plan
[youtube https://www.youtube.com/watch?v=Gtz0Qf7N370] 

= Customer Portal Page = 
ChargeBee's customer portal can also be linked to your WordPress site to allow users to view and update their account and payment information as well as view past invoices. 

Checkout out this video on what is ChargeBee portal and how it can be used
[youtube https://www.youtube.com/watch?v=krgvx1bPzvU]

== Frequently Asked Questions ==

= What is webhook? How to test it in local environment? =

Webhook are the callback service to notify the changes that happen to subscriptions in ChargeBee to WordPress. For more information see <a href="https://www.chargebee.com/docs/events_and_webhooks.html#webhooks">here</a>.

You cannot test webhook in local environment. If you want to check it out you should use local tunneling tool like <a href="https://ngrok.com/ngrok">ngrok</a>. 

= How good secure is this plugin? =

ChargeBee plugin integerates your WordPress with the <a href="https://app.chargebee.com">ChargeBee app</a>. Customer give their card details in checkout page that is hosted in ChargeBee. So, plugin reduces PCI compliance in your WordPress site.

= If plugin uses ChargeBee checkout page, what level of customization can I do in the checkout page? =

Each and every part of ChargeBee hosted page is customizable. You can also customize the look of the checkout page similar to your WordPress site.

= What is ChargeBee customer portal page? =

ChargeBee customer portal page allows your site users to manage their own card information, billing information and also they can see their state of their subscription.

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

= 2.1 =
Short code had been added to show custom messages based on the user plan

= 2.0 =
Option for customer to view the available plans and to switch their plan by themselves. Customers can use the ChargeBee hosted page and customer portal feature.

= 1.1 =
Webhook calls are checked with the existing customer subscription information and if any change found they are fetched from the ChargeBee.

= 1.0 =
Initial version of ChargeBee plugin.


== Upgrade Notice ==

= 2.1 =
Short code had been added to show custom messages based on the user plan
 
= 2.0 =
Option for customer to view the available plans and to switch their plan by themselves. Customers can use the ChargeBee hosted page and customer portal feature.

= 1.1 =
If the webook data is different from wordpress subscription and customer meta then Subscription or Customer retrieve API call is performed and then meta information are updated.

= 1.0 =
This is the initial base version of ChargeBee plugin.


