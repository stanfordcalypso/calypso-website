<?php
include_once "sendgroupme.php";

$TEST_GROUP_ID = "18939895";
$CALYPSO_GROUP_ID = "2218713";
$BASE_URL = "https://api.groupme.com/v3";
$ACCESS_TOKEN = "a2c384a0963a0133dc543ba1d71bc747";
$messages = array("Ayyy Calypso! Practice starts in half an hour! Be at Manz in 20 minutes to set up!",
                  "T-MINUS 30 MINUTES TO PRACTICE.",
                  "Practice. Soon. Be there.",
                  "ZOMG CAN'T WAIT FOR REHEARSAL IN HALF AN HOUR!" 
                );

//$dm_url = $BASE_URL . "/direct_messages" . "?token=" . $ACCESS_TOKEN;
$group_message_url = $BASE_URL . "/groups/" . $TEST_GROUP_ID . "/messages" . "?token=" . $ACCESS_TOKEN;
//$message = $messages[rand() % count($messages)];
$message = $messages[0];
echo $message;
$fields = array(
  'message' => array(
    'source_guid' => strval(rand()),
    'text' => $message
  )
);
print_r($fields);
$data_string = json_encode($fields);
//echo $group_message_url;
//echo $data_string;
send_groupme($group_message_url, $data_string);
?>
