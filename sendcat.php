<?php
include_once "sendgroupme.php";
include_once "downloadcat.php";
include_once "deletecat.php";
include_once "uploadcat.php";
function sendcat() {
  $TEST_GROUP_ID = "18939895";
  $CALYPSO_GROUP_ID = "2218713";
  $BASE_URL = "https://api.groupme.com/v3";
  $ACCESS_TOKEN = "a2c384a0963a0133dc543ba1d71bc747";
  $messages = array("DID SOMEBODY SAY CAT",
                    "I found this for you.",
                    "MEOW."
                  );

  downloadcat();
  $img_url = uploadcat('temp/cat.jpg');
  echo $img_url.'<br/>';
  deletecat();

  //$dm_url = $BASE_URL . "/direct_messages" . "?token=" . $ACCESS_TOKEN;
  $group_message_url = $BASE_URL . "/groups/" . $TEST_GROUP_ID . "/messages" . "?token=" . $ACCESS_TOKEN;
  $message = $messages[rand() % count($messages)];
  //$message = $messages[0];
  echo $message;
  $fields = array(
    'message' => array(
      'source_guid' => strval(rand()),
      'text' => $message,
      "attachments" => array(
        array(
          "type" => "image",
          "url" => $img_url
        )
      )
    )
  );
  $data_string = json_encode($fields);
  echo $data_string . "<br/>";
  //echo $group_message_url;
  //echo $data_string;
  send_groupme($group_message_url, $data_string);
}

sendcat();
?>
