<div class='wrap'><h2>ChargeBee Settings </h2><hr>
  <form action='' method='post'>
   <?php  wp_nonce_field("wp-action-cb-plugin-site-setting","nonce-cb-wordpress-action") ?>
   <h3>Account Settings</h3><hr/>
   <table class="form-table">
      <tr valign="top">
           <th scope="row">
                <label for="tablecell">ChargeBee Site Name</label>*
           </th>
           <td>
               https://<input type='text' name='cb[site_domain]' size='15' placeholder="acme" 
                         value='<?php echo $cboptions["site_domain"] ?>' />.chargebee.com
               <br>
               <span class="description"> Your site name as registered in <a href="https://app.chargebee.com" target="_blank">ChargeBee</a>. When testing configure your test site (eg: acme-test).</span>
           </td>
       </tr>
       <tr valign="top">
           <th scope="row">
                <label for="tablecell">ChargeBee API Key</label>*
           </th>
           <td>
              <input class='regular-text' type='text' size='36' name='cb[api_key]' placeholder='api-key' 
                                  value='<?php echo $cboptions["api_key"] ?>' />
              <br/>
              <span class="description">Your ChargeBee API key with "Full Access" permission.
              </span>
             <br>                 
           </td>
      </tr>
       <tr valign="top">
           <th scope="row">
                <label for="tablecell">Default plan for new signups</label>
           </th>
           <td>
                <input type="text" name='default_plan' size='20' value='<?php echo ( isset($cboptions["default_plan"]) ? $cboptions["default_plan"] : "" ) ?>'
                             placeholder='ChargeBee Plan Id'>
                <br/>
                <span class="description">The specified plan ID will be used for creating a subscription in ChargeBee when a user signs up in WordPress. Only trial or zero dollar plans are allowed.
                </span>   
           </td>
       </tr>
   </table>
       <br>
         <h3>Webhook URL</h3>
       <hr>
       <div class="description">
          The plugin uses Chargebee's <a href="https://www.chargebee.com/docs/events_and_webhooks.html#webhooks" target="_blank">webhooks</a>
          to synchronize the subscription details from ChargeBee with the details in WordPress.
       </div>
       <div class="description"> 
            <br/><strong>Copy the webhook URL below and add it in ChargeBee under "Settings > Webhook settings".</strong> 
       </div>
       <br/>
      <?php 
        $SITE_URL=site_url();
        $WEBHOOK_URL = explode("://", $SITE_URL);
        $CBAUTH_USER = $cboptions["webhook_user_auth"];
        $CBAUTH_PASSWD = $cboptions["webhook_user_pass"];
      ?>
      <code> 
         <?php echo $WEBHOOK_URL[0] . "://" . $CBAUTH_USER . ":" . $CBAUTH_PASSWD . "@" . $WEBHOOK_URL[1] ?>?chargebee_webhook_call=true
      </code> 
       <br/>
       <br/>
       <strong>Note:</strong> Plugin uses "Basic Authentication" mechanism to authenicate the webhook calls. 
        <a href="#" id="show-credentials">Click here</a> to change the credentials.
         <br/><br/>
       <div id="webhook-credentials" style="display: none" >
         The username and password for webhook call are : 
         <table class="form-table">
           <tr valign="top">
               <th scope="row">
                   <label for="tablecell">Username</label>*
               </th>
               <td>
                    <input class='regular-text' type='text' size='15' name='cb[webhook_user_auth]' 
                                value='<?php echo $cboptions["webhook_user_auth"] ?>' />
               </td>
           </tr>
           <tr valign="top">
               <th scope="row">
                     <label for="tablecell">Password</label>*
               </th>
               <td>
                    <input class='regular-text' type='text' size='36' name='cb[webhook_user_pass]' 
                           value='<?php echo $cboptions["webhook_user_pass"] ?>' />
               </td>
           </tr>
         </table>
         <strong> Note: </strong> The username and password above can be changed if required. This will regenerate a webhook URL when the changes are saved.
       </div>
      <br/>
     <br/>
      <div><h3> Return & Cancel URL </h3> </div>
      <hr>
      <div class="description">
         The URL users will be redirected to when they either signup successfully or cancel before completing the checkout.
      <br/> <br/> 
        <strong> Copy the URL below and paste it in the "Return URL and "Cancel URL" fields in ChargeBee under "Settings > Hosted Page Settings > Hosted Checkout Page". </strong>
      </div>
      <br>
       <code>
           <?php echo site_url() ?>?chargebee_redirection=true
       </code>
      <br/>
      <p class="submit">
            <input name="submit" type="submit" value="Save Changes" class="button-primary">
      </p>
    </form>
</div>

