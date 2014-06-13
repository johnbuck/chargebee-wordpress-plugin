<?php 
   require 'ChargeBee.php';
   ChargeBee_Environment::$scheme = "https";
   ChargeBee_Environment::$chargebeeDomain = "devcb.in";

   ChargeBee_Environment::configure("kumar-test", "test_ccdkt77iqIWoQoje2URcd5vgFo14xTZtEB");

   function retrieveAllSub() {
    $all = ChargeBee_Subscription::all(array("limit" => 2));
//    var_dump($all);
   echo $all->count();
    echo $all->nextOffset(); 
   }
  
  function retrieveSub() {
   $result = ChargeBee_Subscription::retrieve("Hs7qjmiOXAUvMuUZ");
   var_dump($result);
  }
  retrieveAllSub();
//  retrieveSub();
?>

