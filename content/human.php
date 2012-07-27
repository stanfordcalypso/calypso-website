<?php

$human = array(
	       "1" => "4 * 8;32",
	       "2" => "What is the first month of the year?;january",
	       "3" => "Is pluto a planet?;no",
	       "4" => "A jar contains only red marbles and green marbles. If a marble is selected at random from the jar, the probability that a red marble will be selected is 2/3. If there are 36 green marbles in the jar, how many red marbles are there in the jar?;72",
	       "5" => "Which set of words best fits the meaning of the sentence as a whole?<br />\"My main criticism of the artist's rendering of the ancient mammal's physical appearance is that, unsupported by even a ----- of fossil evidence, the image is bound to be -----.\"<p></p>(a) modicum..speculative<br />(b) particle..supplemented<br />(c) persual..substantiated<br />(d) fabricated..obsolete<br />(e) recapitulation..exhausted;a",
	       "6" => "If there are 10 dogs and 20 cats and 2/3 of the cats die, how many dogs are there?;10",
	       "7" => "What is the rightmost letter on your keyboard?;p",
	       "8" => "Calypso has $1000 and and you give us another $1000. How much does calypso have now?;2000:$2000",
	       "9" => "(4 + 8) * 3;36",
	       "10" => "What is the 4th letter of the word \"calypso\"?;y",
	       "11" => "Type \"yes\";yes",
	       "12" => "Type \"no\";no",
	       "13" => "1 + 2 + 3;6",
	       "14" => "What is the last month of the year?;december",
	       "15" => "45 / 9;5",
	       "16" => "The day after Sunday;monday",
	       "17" => "What is the third color of the rainbow?;yellow",
	       "18" => "How many states are there in the U.S.?;50",
	       "19" => "Which one is a walrus?<table><tr><td>A:<br /><img src='pics/penguin.jpg'></td></tr><tr><td>B:<br /><img src='pics/walrus.jpg' width='300' height='200'></td></tr><tr><td>C:<br /><img src='pics/pikachu.png'></td></tr></table>;b",
	       "20" => "Who is this?<br /><iframe width=\"420\" height=\"315\" src=\"http://www.youtube.com/embed/C-u5WLJ9Yk4\" frameborder=\"0\" allowfullscreen></iframe>;britney spears:brittney spears;britney:brittney",
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