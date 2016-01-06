<?php
$TUCKBUTT_ID = '13863978';
$BASE_URL = "https://api.groupme.com/v3";
$ACCESS_TOKEN = "a2c384a0963a0133dc543ba1d71bc747";
//$response = http_get("https://api.groupme.com/v3/groups?token=" . $ACCESS_TOKEN);
$response = file_get_contents("https://api.groupme.com/v3/groups?token=" . $ACCESS_TOKEN);
//$response = http_get("https://api.groupme.com/v3/groups?token=a2c384a0963a0133dc543ba1d71bc747");
if (gettype($response) == "string") {
  echo "Got Response <br/>";
}
echo $response;
echo "Hello!<br/>";
$dm_url = $BASE_URL . "/direct_messages" . "?token=" . $ACCESS_TOKEN;
//$ch = curl_init($dm_url);
////$fields = array(
  ////'http'=>array(
    ////'method'=>"GET",
    ////'header'=>"Accept-language: en\r\n" .
                        ////"Cookie: foo=bar\r\n"
  ////)
////);
$fields = array(
  'direct_message' => array(
    'source_guid' => strval(rand()),
    'recipient_id' => $TUCKBUTT_ID,
    'text' => 'HI FROM CALYPSOBOT'
  )
);
$data_string = json_encode($fields);
////var_dump($fields);
//$fields_str = http_build_query($fields);
//echo $fields_str;
//curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_str);
//curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
////execute post
//$result = curl_exec($ch);

include "../sendgroupme.php";
send_groupme($dm_url, $data_string);

//$ch = curl_init();
//$curlConfig = array(
    //CURLOPT_URL            => $dm_url,
    //CURLOPT_POST           => true,
    //CURLOPT_RETURNTRANSFER => true,
    //CURLOPT_POSTFIELDS     => $data_string,
    //CURLOPT_HTTPHEADER     => array(                                                                          
      //'Content-Type: application/json',                                                                                
      //'Content-Length: ' . strlen($data_string),
    //),
//); 
//curl_setopt_array($ch, $curlConfig);
//$result = curl_exec($ch);

//// result sent by the remote server is in $result
//$er = curl_error($ch);
//if ($er) {
  //echo "Curl error: ". $er . "<br/>";
//}
//$reponseInfo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
//echo $responseInfo;
////$httpResponseCode = $responseInfo['http_code'];
////echo $httpResponseCode . "<br/>;
//if ($result) {
  //echo "Got result<br/>";
  //echo $result;
//}

////close connection
//curl_close($ch);
//echo "Done! <br/>";
?>
