<?php
include_once 'sendcat_bot.php';
include_once 'sendnumberfact.php';
//include 'sendjoke.php';
include_once 'sendrandomfact.php';
include_once 'email.php';

echo 'HI<br/>';
$message = "";
foreach (getallheaders() as $name => $value) {
  $message .= "$name: $value<br/>";
}
$params = json_decode(file_get_contents('php://input'), true);
foreach ($params as $name => $value) {
  $message .= "$name: $value<br/>";
}
//send_email('Tucker', 'tuckerl@stanford.edu', 'POST body', $message);
if (strcmp($params['name'], 'CalypsBot') != 0
  && preg_match("/(?<![A-z])CalypsBot(?![A-z])/i", $params['text']) > 0) {
  if (preg_match("/(?<![A-z])cat(?![A-z])/i", $params['text']) > 0 ) {
    send_email('Tucker', 'tuckerl@stanford.edu', 'POST body', "CAT");
    //$new_msg = $_POST['text'];  
    //if (preg_match('/.*CalypsBot.*/i', $new_msg) && preg_match('/.*cat.*/i', $new_msg)) {
      //include 'send_catbot.php';
      //sendcat();
    //}
    sendcat();
  }
  if (preg_match("/\?$/i", $params['text']) > 0) {
    if (preg_match_all("/(\d+)/i", $params['text'], $matches) > 0) {
      send_email('Tucker', 'tuckerl@stanford.edu', 'POST body', "NUMBER");
      $num = $matches[0][rand() % count($matches[0])];
      sendnumberfact($num);
    } else {
      send_email('Tucker', 'tuckerl@stanford.edu', 'POST body', "RANDOM FACT");
      sendrandomfact();
    }
  } else if (preg_match("/(?<![A-z])fact(?![A-z])/i", $params['text']) > 0) {
    send_email('Tucker', 'tuckerl@stanford.edu', 'POST body', "RANDOM FACT");
    sendrandomfact();
  }
} else {
  echo "Nope.";
}

?>
