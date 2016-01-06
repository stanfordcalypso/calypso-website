<?php
include_once "sendgroupme.php";
include_once "downloadcat.php";
include_once "deletecat.php";
include_once "uploadcat.php";
function sendcat() {
  $TEST_GROUP_ID = "18939895";
  $CALYPSO_GROUP_ID = "2218713";
  $TEST_BOT_ID = "26d427f227c4d588e040b04d85";
  $BOT_ID = 'a89eeb967765a37fa22e2eb31a';
  $BASE_URL = "https://api.groupme.com/v3";
  $ACCESS_TOKEN = "a2c384a0963a0133dc543ba1d71bc747";
  $messages = array("DID SOMEBODY SAY CAT",
                    "MEOW",
                    "LOOK WHAT I FOUND"
                  );
  $CAT_FILE = 'temp/cat.jpg';
  
  if (!file_exists($CAT_FILE) || filesize($CAT_FILE) == 0) {
    echo "Uh oh, file not found";
    return;
  }
  $img_url = uploadcat($CAT_FILE);
  echo $img_url.'<br/>'; 

  //$dm_url = $BASE_URL . "/direct_messages" . "?token=" . $ACCESS_TOKEN;
  $group_message_url = $BASE_URL . "/bots/post";
  $message = $messages[rand() % count($messages)];
  //$message = $messages[0];
  echo $message;
  $fields = array(
    'bot_id' => $BOT_ID,
    'text' => $message,
    "attachments" => array(
      array(
        "type" => "image",
        "url" => $img_url
      )
    )
  );
  $data_string = json_encode($fields);
  echo $data_string . "<br/>";
  //echo $group_message_url;
  //echo $data_string;
  send_groupme($group_message_url, $data_string);
  
  deletecat();
  $i=0; $LIM = 10;
  while (!file_exists($CAT_FILE) || filesize($CAT_FILE) == 0) {
    downloadcat($CAT_FILE);
    $i = $i + 1;
    if ($i > $LIM) break;
  }
}

?>
