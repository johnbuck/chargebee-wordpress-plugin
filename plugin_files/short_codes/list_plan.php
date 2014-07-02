<?php
/*
 * Generates a sample plan page.
 */
global $cboptions; 
try {
?>
<table class="cb-table">
 <tr>
   <th>Plan Name</th>
   <th>Plan Description</th?>
   <th>Plan Price </th>
   <th> </th>
 </tr>
<?php
   $nextIteration = true;
   $limit = 30;
   $offset = null;
   $current_user = wp_get_current_user();
   $cb_current_plan = apply_filters("cb_get_user_plan", $current_user->ID);
   while ($nextIteration) :
       $all = ChargeBee_Plan::all(array("limit" => $limit, "offset" => $offset ));
       foreach($all as $entry ) {
           $plan = $entry->plan();
            if( $plan->status == "archived" ) {
		  continue;
            }
            $is_current_plan = isset($cb_current_plan) && $cb_current_plan== $plan->id ?>  
            <tr class="<?php echo $is_current_plan ? "cb-current-plan": "" ?>">
	         <td> <?php echo isset($plan->invoiceName) ? $plan->invoiceName : $plan->name ?> </td>
	         <td> <?php echo plan_description($plan, $cboptions["currency"]) ?> </td>
	         <td> <?php echo plan_price($plan, $cboptions["currency"]) ?> </td>
		 <td> <?php if( $is_current_plan) { ?> 
                       Current Plan   
                     <?php } else {  ?>
                       <a href="<?php echo "?chargebee_plan_id=" . $plan->id ?>" > Choose </a>  
                     <?php } ?>
                 </td>
	    </tr>
       <?php }
        if( $all->nextOffset() == null) {
             $nextIteration = false;
             break;
        } else {
             $offset= $all->nextOffset();
        }
    endwhile;
?> 
</table>
<?php 
} catch (ChargeBee_APIError $e) { ?>
  </table>
  <?php 
    if (is_admin() ) { ?>
      <div class="cb-text-failure"> Couldnt find any plans in your ChargeBee site </div> 
   <?php } else { ?>
      <div class="cb-text-failure"> No plan found </div> 
<?php } 
} ?>

<?php 
function plan_description($plan, $currency) {
   $plan_desc = "";
   if( isset($plan->trialPeriod) && $plan->trialPeriod != 0) {
       $plan_desc .= $plan->trialPeriod . " " . $plan->trialPeriodUnit . " trial";
   } else {
       $plan_desc .= "No trial";
   }

   if( isset($plan->freeQuantity) && $plan->freeQuantity != 0) {
      $plan_desc .= $plan->freeQuantity . " free quantities";
   }
  
   if( isset($plan->setupCost) ) {
     $plan_desc .= " " . $currency . " " . number_format($plan->setupCost/100, 2, '.', ''). " setup cost";
   }
   return $plan_desc;   

}

function plan_price($plan, $currency) {

  if( isset($plan->price) && $plan->price != 0 ) {
    return $currency . " " . number_format($plan->price/100, 2, '.', '') . " per " . $plan->periodUnit ;
  } else {
    if( isset($plan->setupCost) && $plan->setupCost != 0 ) {
      return "Only setup Charge";
    } else {
      return "Free Plan";
    }
  }
 
} 
?>
