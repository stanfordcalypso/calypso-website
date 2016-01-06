<?php
include 'sendcat_bot.php';
echo 'HI';
$message = "";
foreach (getallheaders() as $name => $value) {
  $message .= "$name: $value<br/>";
}
include 'email.php';
$params = json_decode(file_get_contents('php://input'), true);
foreach ($params as $name => $value) {
  $message .= "$name: $value<br/>";
}
if (strcmp($params['name'], 'CalypsBot') != 0
  && preg_match("/CalypsBot/i", $params['text']) > 0
  && preg_match("/cat/i", $params['text']) > 0 ) {
  send_email('Tucker', 'tuckerl@stanford.edu', 'POST body', $message);
  //$new_msg = $_POST['text'];  
  //if (preg_match('/.*CalypsBot.*/i', $new_msg) && preg_match('/.*cat.*/i', $new_msg)) {
    //include 'send_catbot.php';
    //sendcat();
  //}
  sendcat();
} else {
  echo "Nope.";
}

?>
