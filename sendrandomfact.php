<?php
include_once "sendgroupme.php";
include_once "getrandomfact.php";
include_once "email.php";

function sendrandomfact() {
  $BOT_ID = 'a89eeb967765a37fa22e2eb31a';
  //$TEST_BOT_ID = 'db00b12f324742cb53ab893673';

  $fact = get_fact();
  echo $fact[0] . " " . $fact[1] . "<br/>";
  if (preg_match('/TMAF about (.+)$/i', $fact[0], $matches) < 1) {
    echo "Uh oh, bad title<br/>";
  } else {
    echo $matches[1];
    $fields = array(
      'bot_id' => $BOT_ID,
      //'bot_id' => $TEST_BOT_ID,
      'text' => "Here's a fact about " . $matches[1]
    );
    $data_string = json_encode($fields);

    $groupme_url = "https://api.groupme.com/v3/bots/post";
    send_groupme($groupme_url, $data_string);
    //send_email("Tucker", "tuckerl@stanford.edu", "Random Fact 1", $matches[1]);
  }
  $fields = array(
    'bot_id' => $BOT_ID,
    //'bot_id' => $TEST_BOT_ID,
    'text' => $fact[1]
  );
  $data_string = json_encode($fields);
  $groupme_url = "https://api.groupme.com/v3/bots/post";
  send_groupme($groupme_url, $data_string);
  //send_email("Tucker", "tuckerl@stanford.edu", "Random Fact 2", $fact[1]);
}

//sendrandomfact();

?>
