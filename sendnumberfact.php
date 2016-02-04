<?php
include_once "sendgroupme.php";
include_once "email.php";

function sendnumberfact($n) {
  $BASE_URL = 'http://numbersapi.com/';
  $BOT_ID = 'a89eeb967765a37fa22e2eb31a';
  //$TEST_BOT_ID = 'db00b12f324742cb53ab893673';

  $ch = curl_init();
  $curlConfig = array(
      CURLOPT_URL            => $BASE_URL . strval($n),
      CURLOPT_RETURNTRANSFER => true,
  ); 
  curl_setopt_array($ch, $curlConfig);
  $fact = curl_exec($ch);
  echo $fact . "<br/>";

  $prefixes = array(
    "Uhh, I'm not sure, but ",
    "I don't know that, but ", 
    "Haha, I have no idea, but "
  );
  $prefix = $prefixes[rand() % count($prefixes)];
  $fields = array(
    'bot_id' => $BOT_ID,
    //'bot_id' => $TEST_BOT_ID,
    'text' => $prefix . $fact
  );
  $data_string = json_encode($fields);

  $groupme_url = "https://api.groupme.com/v3/bots/post";
  send_groupme($groupme_url, $data_string);
  //send_email("Tucker", "tuckerl@stanford.edu", "Groupme Number Fact Test", $prefix . $fact);

}

//sendnumberfact(39);

?>
