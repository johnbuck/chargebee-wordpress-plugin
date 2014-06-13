<?php

/*
 * Redirect to the passed URL
 */
function redirect_to_url($url) {
  echo "<script>window.location = \"" . $url .  "\"</script>";
}

/*
 * Appends query param and creates page link based on premalink. 
 */
function generate_page_link($perma_link, $query_param) {
 $pos = strpos($perma_link,"?");
  if( $pos === false ) {
   return $perma_link . "?" . $query_param;
  } else {
   return $perma_link . "&" . $query_param;
  }

}

?>
