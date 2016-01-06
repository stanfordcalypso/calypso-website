<?php
function send_groupme($url, $field_string) {
  $ch = curl_init();
  //echo $url;
  //echo $field_string;
  $curlConfig = array(
      CURLOPT_URL            => $url,
      CURLOPT_POST           => true,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_POSTFIELDS     => $field_string,
      CURLOPT_HTTPHEADER     => array(                                                                          
        'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($field_string),
      ),
  ); 
  curl_setopt_array($ch, $curlConfig);
  $result = curl_exec($ch);

  // result sent by the remote server is in $result
  $er = curl_error($ch);
  if ($er) {
    echo "Curl error: ". $er . "<br/>";
  }
  $reponseInfo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  echo $responseInfo;
  //$httpResponseCode = $responseInfo['http_code'];
  //echo $httpResponseCode . "<br/>;
  if ($result) {
    echo "Got result<br/>";
    echo $result;
  }

  //close connection
  curl_close($ch);
  echo "Done! <br/>";
  return($result);
} 

?>
