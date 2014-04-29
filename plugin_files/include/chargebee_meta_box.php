<strong>Content is public by default. To make it private, choose one or more plans from below. Subscribers on the specific plans will be able to access this content. </strong>
<br>
<input type='hidden' name='access_noncename' id='access_noncename' value='<?php echo $nonce_value ?>'/>
<?php try {
           $limit = 10;
           $offset = null;
           $nextIteration = true;
           $isPlanFound = false;
            while($nextIteration) {
               $all = chargebee_plan::all(array("limit" => $limit, "offset" => $offset));
               foreach($all as $entry) {
                   $isPlanFound = true;
                   $plan = $entry->plan(); ?> 
                   <input type='checkbox' name="cbplans[<?php echo $plan->id ?>]"
                        value='<?php echo $plan->invoiceName ?>'  <?php echo ( isset($plans[$plan->id])  ?"checked":"") ?>  />   
                   <?php echo $plan->invoiceName ?>
                   <?php echo ($plan->status =="archived" ? "(Archived Plan)" : "") ?>
                   <br>
               <?php } ?>
               <?php 
                 if ( $all->nextOffset() == null) { 
                    $nextIteration = false;
                  } else {
                    $offset = $all->nextOffset();
                  }  ?>
           <?php } 
           if( !$isPlanFound ) { ?>
               <div> No Plans found in your ChargeBee site</div>
          <?php } 
        } catch( ChargeBee_APIError $e) { 
            $jsonError = $e->getJsonObject();   
            echo $jsonError['error_msg'];
    } ?>

