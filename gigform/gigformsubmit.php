
<?php

include "../email.php";
include "human.php";

function printback() {
	 echo "<div style='padding:10px'><a href='gigform.php' style='color:black'>Back to form</a></div>";
}

if (isset($_POST[name]) && !empty($_POST[name]) &&
isset($_POST[email]) && !empty($_POST[email]) &&
isset($_POST[phone]) && !empty($_POST[phone]) &&
isset($_POST[month]) && !empty($_POST[month]) &&
isset($_POST[day]) && !empty($_POST[day]) &&
isset($_POST[year]) && !empty($_POST[year]) &&
isset($_POST[time]) && !empty($_POST[time]) &&
isset($_POST[duration]) && !empty($_POST[duration]) &&
isset($_POST[description]) && !empty($_POST[description]) &&
isset($_POST[q]) && !empty($_POST[q]) &&
isset($_POST[human]) && !empty($_POST[human])) {
  if (is_human($_POST[q], $_POST[human])) {
  $message = "Name: " . $_POST[name] . "<br />" .
  "Email: " . $_POST[email] . "<br />" .
  "Phone: " . $_POST[phone] . "<br />" .
	"Event description: " . $_POST[description] . "<br />" .
  "Date: " . $_POST[month] . " " . $_POST[day] . " " . $_POST[year] . "<br />" .
  "Time: " . $_POST[time] . "<br />" .
	"Performance duration: " . $_POST[duration] . "<br />" .
  "Location: " . $_POST[location] . "<br />" .
  "Comments: " . $_POST[comments] . "<br />";

  //send_email_with_reply_to($_POST[name], "jesseruder@gmail.com", "Cardinal Calypso Booking Request", $message, $_POST[name], $_POST[email]);
  send_email_with_reply_to($_POST[name], "stanfordcalypso@gmail.com", "Cardinal Calypso Booking Request", $message, $_POST[name], $_POST[email]);
  echo "Thank you for your request! We will contact you shortly.";
  }
  else {
    echo "Could not verify you are a human.";
    printback();
  }
}
else {
  echo "Please enter all the required information.";
  printback();
}

?>
