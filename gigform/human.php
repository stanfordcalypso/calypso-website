<?php

$human = array(
	       "1" => "4 * 8;32",
	       "2" => "What is the first month of the year?;january",
	       "3" => "(4 + 8) * 3;36",
	       "4" => "What is the 4th letter of the word \"calypso\"?;y",
	       "5" => "1 + 2 + 3;6",
	       "6" => "What is the last month of the year?;december",
	       "7" => "45 / 9;5",
	       "8" => "The day after Sunday;monday",
	       );

function get_question() {
  global $human;

  $c = count($human);
  $n = rand(1, $c);
  $q = explode(";", $human[$n]);
  $n2 = $n + ($c * rand(1,100));

  return "<input type='hidden' name='q' value='" . $n2 . "'>" . $q[0] . "<br /><div style='padding-top:10px'>Answer: <input name='human'></div>";
}

function is_human($num, $answer) {
  global $human;

  $num2 = $num % count($human);
  $result = false;
  $q = explode(";", $human[$num2]);
  $all = $q[1];
  $answers = explode(":", $all);

  for ($i = 0; $i < count($answers); $i = $i + 1) {
    if (strcmp(strtolower($answer), $answers[$i]) == 0) {
      $result = true;
    }
  }

  return $result;
}

?>
