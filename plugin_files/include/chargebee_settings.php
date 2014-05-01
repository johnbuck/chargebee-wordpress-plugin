<div class='wrap'><h2>ChargeBee Settings</h2><hr>
  <form action='' method='post'>
   <?php  wp_nonce_field("wp-action-cb-plugin","nonce-cb-wordpress-action") ?>
   <h3>Account Settings</h3><hr/>
   <table class="form-table">
      <tr valign="top">
           <th scope="row">
                <label for="tablecell">Your ChargeBee Site Name:</label>
           </th>
           <td>
               <input class='regular-text' type='text' size='15' name='cb[site_domain]' 
                                  value='<?php echo $cboptions["site_domain"] ?>' />
               <br>
               <span class="description"> Your site name as registered in <a href="https://app.chargebee.com" target="_blank">ChargeBee</a>. For testing purpose please provide your test site (eg: acme-test).</span>
           </td>
       </tr>
       <tr valign="top">
           <th scope="row">
                <label for="tablecell">Your API Key:</label>
           </th>
           <td>
              <input class='regular-text' type='text' size='36' name='cb[api_key]' 
                                  value='<?php echo $cboptions["api_key"] ?>' />
              <br/>
              <span class="description">Your api key. Please see the 
                   <a target="_blank" href="https://www.chargebee.com/docs/api_keys.html">documentation</a> for more details.
              </span>
             <br>                 
           </td>
      </tr>
       <tr valign="top">
           <th scope="row">
                <label for="tablecell">Default plan for Customer Registration/Subscription:</label>
           </th>
           <td>
                <input name='default_plan' value='<?php echo $cboptions["default_plan"] ?>'
                             placeholder='Plan Id in ChargeBee'>
                <br/>
                <span class="description">Optional field. Specify a default ChargeBee plan_id to signup new users in ChargeBee.</span>
                <br/>
                <span class="description"><strong>Note: </strong> If default plan_id is not provided, then a corresponding subscription will not be created in ChargeBee. You would have to explicitly call create subscription using the api later.
                </span>   
           </td>
       </tr>
   </table>
   <br/>
   <h3>Access control messages</h3>
   <hr/>
   <div class="description">You may restrict the content (or pages) that can be viewed by a user based on their plan. Specify the error messages to be displayed when a user tries to access a restricted content, not available to them. </div>
   <table class="form-table">
       <tr valign="top">
           <th scope="row">
                <label for="tablecell">'Not logged in' Message:</label>
           </th>           
            <td>
                <textarea name='cb[not_logged_in_msg]' rows='3' cols='50'><?php echo $cboptions["not_logged_in_msg"] ?></textarea>
                <br/>
                <span class="description">The message to be displayed when a user, who has not yet logged in, is trying to access a post/page that has been restricted. </span>
           </td>
       </tr>
       <tr valign="top">
           <th scope="row">
                <label for="tablecell">'No Access' Message</label>
           </th>
           <td>
                <textarea name='cb[no_access_msg]' rows='3' cols='50'><?php echo $cboptions["no_access_msg"] ?></textarea>
                <br/>
                <span class="description">Message displayed for "logged-in" users but they do not have access to the content.</span>
           </td>
       </tr>
        <tr valign="top">
           <th scope="row">
                <label for="tablecell">'Canceled' Message</label>
           </th>
           <td>
                <textarea name='cb[cancel_msg]' rows='3' cols='50'><?php echo $cboptions["cancel_msg"] ?></textarea>
                <br/>
               <span class="description">Message displayed when a user is "logged-in" but the subscription is in canceled state. In this plugin, when a Subscription is cancelled, the associated ChargeBee plan information is just removed in wordpress thus resulting in restriction of access.</span>
           </td>
       </tr>
      </table>
       <br>
         <h3>Webhook URL</h3>
       <hr>
       <div class="description">The plugin uses webhooks to synchronize the subscription details from ChargeBee with wordpress meta information. <a href="https://www.chargebee.com/docs/events_and_webhooks.html#webhooks" target="_blank">More on webhooks</a><br/><br/><strong>Please copy the below url and update it in the ChargeBee webhook settings.</strong> 
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
        <br>
       </code>
       <br/>
       <h3>Basic authentication settings for webhook</h3>
       <hr>
       <div class="description">The BASIC authenticaton settings for webhook. By default the password is auto generated at the time of installation. If you change the user name or password then please save the changes for it to be reflected in the webhook url above. Then update the <strong>modified url</strong> in ChargeBee.</div>
       <table class="form-table">
           <tr valign="top">
               <th scope="row">
                   <label for="tablecell">Username:</label>
               </th>
               <td>
                    <input class='regular-text' type='text' size='15' name='cb[webhook_user_auth]' 
                                value='<?php echo $cboptions["webhook_user_auth"] ?>' />
               </td>
          </tr>
          <tr valign="top">
               <th scope="row">
                     <label for="tablecell">Password:</label>
               </th>
               <td>
                    <input class='regular-text' type='text' size='36' name='cb[webhook_user_pass]' 
                           value='<?php echo $cboptions["webhook_user_pass"] ?>' />
               </td>
          </tr>
       </table>
       <br>
      <p class="submit">
            <input name="submit" type="submit" value="Save Changes" class="button-primary">
      </p>
    </form>
</div>

