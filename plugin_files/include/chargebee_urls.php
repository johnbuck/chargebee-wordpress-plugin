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
</div>
