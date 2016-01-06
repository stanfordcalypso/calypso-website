<?php

include_once './sendgroupme.php';

function uploadcat($cat_file) {
  $cat_file = str_replace("uploadcat.php", $cat_file, __FILE__);
  $BASE_URL = "https://image.groupme.com/pictures";
  $ACCESS_TOKEN = "a2c384a0963a0133dc543ba1d71bc747";
  // initialise the curl request
  $request = curl_init($BASE_URL . '?access_token=' . $ACCESS_TOKEN);

  // send a file
  curl_setopt($request, CURLOPT_POST, true);
  curl_setopt(
      $request,
      CURLOPT_POSTFIELDS,
      array(
        'file' => '@' . $cat_file 
      ));

  // output the response
  curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
  $json_str = curl_exec($request);

  // close the session
  curl_close($request);

  echo $json_str . "<br/>";
  $result = json_decode($json_str, true);
  //print_r($result);
  return $result['payload']['url'];
}

//$url = uploadcat("temp/cat.jpg");
//echo $url . "<br/>";

?>
