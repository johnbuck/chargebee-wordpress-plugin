<div class='wrap'>
<h2> Integration with user registration Plugins </h2>
<hr>
<div class="description">
ChargeBee plugin integrates with other wordpress plugins that offer user registration. Thus, when a user signs up, a new subscription gets created for your default plan and also allows you to send additional user information to ChargeBee.
<br/>
<strong>Note: </strong> By default, this plugin sends the customer's first name, last name and email that's saved in WordPress as customer information to ChargeBee.
<br/><br/>
To complete this integration, please follow the steps given below -
<div>
<h4>1. Install and activate the user registration plugin.</h4>
<h4>2. Click the following button to start capturing registration fields </h4>
 <div>
 <form action="" method="post">
	<?php  wp_nonce_field("wp-action-cb-plugin-page-setting","nonce-cb-wordpress-action") ?>
	<?php $store_register_param = isset($cboptions['store_register_param']) && $cboptions['store_register_param'] == "true" ?>
	<input type="hidden" name="cb[store_register_param]" value="<?php echo $store_register_param? "false" : "true" ?>" />
	<input type="submit" <?php echo $store_register_param? "disabled":""?> value="Start capturing" class="button-primary"/>
 </form>
</div>
<h4>3. Do a sample registration on your site. So that the registration fields and their values can be saved and displayed as JSON below. </h4>
<div class="description"> Captured registration fields :
</div>
 <?php if(json_encode(get_option("cb_user_register_param")) != "false") { ?>
    <pre><?php echo json_encode(get_option("cb_user_register_param"), JSON_PRETTY_PRINT) ?></pre>
	<div class="description">The JSON shown above is captured during sample registration. The key is the field name and value is the input entered for field name during sample registration.</div>
 <?php } else {  ?>
	  <br/>
     <div class="warning"><i>Registration fields haven't been captured. To capture the fields click "Start capturing" button, do a sample registration once and then click "Stop Capture" button.</i></div>
 <?php  } ?>
 <h4>4. Click the following button to stop capturing the registration fields</h4>
 <div>
  <form action="" method="post">
 	<?php  wp_nonce_field("wp-action-cb-plugin-page-setting","nonce-cb-wordpress-action") ?>
 	<?php $store_register_param = isset($cboptions['store_register_param']) && $cboptions['store_register_param'] == "true" ?>
 	<input type="hidden" name="cb[store_register_param]" value="<?php echo $store_register_param? "false" : "true" ?>" />
 	<input type="submit" <?php echo $store_register_param? "":"disabled "?> value="Stop capturing" class="button-primary"/>
  </form>
</div>
 <h4>5. Provide the mapping JSON as input with key as ChargeBee customer attribute and value as the field name captured in sample registration </h4>
<div class="description">
	To send additional user information to ChargeBee, please provide input as JSON with key as <a href="https://apidocs.chargebee.com/docs/api/customers#customer_attributes" target="_blank">ChargeBee customer attribute</a> and value as field name captured in sample registration. The JSON maps the ChargeBee customer attribute with the user registration field.<br/> <br/>
<strong>Syntax:</strong>
<pre>
{
  "ChargeBee customer attribute 1" : "&lt;field name 1 used in user registration form&gt;",
  "ChargeBee customer attribute 2" : "&lt;field name 2 used in user registeration form&gt;"
}
</pre>
<strong>Example:</strong> <br/>
		In the following sample mapping JSON - "first_name" and "phone" is a ChargeBee customer attribute and "param_first_name" and "param_phone" is the field name used in the user registration form. This mapping JSON will add first name and phone value to the customer created in ChargeBee.   <br/> 
<pre>
{
  "first_name" : "param_first_name",
  "phone" : "param_phone"
}
</pre>
 
</div> 
<br/>
<form action="" method="post">
 <?php  wp_nonce_field("wp-action-cb-plugin-page-setting","nonce-cb-wordpress-action") ?>
   <label>Enter your mapping JSON here: </label>
   <br/>
   <textarea name="cb[cf_param_map]" rows='6' cols='50'><?php echo isset($cboptions['cf_param_map']) ? htmlspecialchars(json_encode($cboptions['cf_param_map'], JSON_PRETTY_PRINT)): "" ?></textarea>
   <br/>
   <input type="submit" value="Map fields" class="button-primary"/>
</form>
<br/>
For any queries contact <a href="mailto:support@chargebee.com">support@chargebee.com</a>		


</div>
