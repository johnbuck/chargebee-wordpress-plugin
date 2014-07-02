<div class='wrap'>
<h2> Plugin URLs </h2>
<hr>
<div class="description"> These URLs can be added as links on your website to forward users to one of the pages below.</div>
<table class="form-table">
 <tr valign="top">
   <th scope="row">
      <label for="cb[plan_page]"> Change Plan URL </label>
   </th>
   <td>
     <code><?php echo site_url() ?>?chargebee_plan_id=<i>&ltchargebee plan id&gt</i></code>
    <br/>
    <span class="description"> 
         Use this URL and include appropriate plan IDs to create links if you’re using your own page for listing the available plans. If customers already have a valid card in ChargeBee, the subscription will be updated. Otherwise the customers will be taken to the hosted checkout page.
   </span>
   </td>
 </tr>
 <tr valign="top">
   <th scope="row"> 
      <label for="cb[account_page]"> Customer Portal URL </label>
   </th>
   <td>
     <code><?php echo site_url() ?>?chargebee_portal=true</code>
    <br/>
    <span class="description"> 
      This URL will forward customers to ChargeBee's <a target="_blank" href="https://www.chargebee.com/docs/customer_portal.html">customer portal</a>. 
    </span>
   </td>
 </tr> 
 <tr valign="top">
   <th scope="row"> 
      <label for="cb[account_page]"> Plan Page URL </label>
   </th>
   <td>
     <code><?php echo site_url() ?>?chargebee_plan_page=true</code>
    <br/>
    <span class="description"> This URL will redirect your customer to the plan listing page configured in Page Settings </a>. 
    </span>
   </td>
 </tr>
</table>
<h2> Short Codes </h2>
<hr>
<div class="description"> The plugin supports short codes that can be embedded into posts or pages on your site. This can be used to display messages or content to users based on their current plan. </div>
<br/>
<li>
Specify the plan id(s) using the shortcode "cb_decision" with the attribute "current_plan_in". Users on the specified plan ID will see the message included. 
<br/><br/>
Syntax : <pre>[cb_decision current_plan_in="&ltplan_id-1&gt,&ltplan_id-2&gt"] Message Content [/cb_decision]</pre>
E.g : <pre>[cb_decision current_plan_in="silver,bronze"] Upgrade to the Gold plan to continue to get access to the content. [/cb_decision] </pre>
</li> 
<li> Specify the plan id(s) using the shortcode "cb_decision" with the attribute "current_plan_not_in". Users NOT on the specified plan ID will see the message included.
<br/><br/>
Syntax : <pre>[cb_decision current_plan_not_in="&ltplan_id-1&gt,&ltplan_id-2&gt"] Message Content [/cb_decision]</pre>
E.g : <pre>[cb_decision current_plan_not_in="gold"] Upgrade to the Gold plan to continue to get access to the content. [/cb_decision] </pre>
</li>
</div>
