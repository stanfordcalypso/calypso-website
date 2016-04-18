<?php

  /*
   * This file is used for the automated gig reminder system.
   * Tucker's Xenon machine has a cron job set to run this every
   * day at 5 pm
   */

include_once "dbcon.php";
include_once "email.php";
include_once "recommendedsongs.php";

function emailtucker($subject, $message) {
  send_email("Tucker", "tuckerl@stanford.edu", $subject, $message);
}

function deleteoldgigs() {
  $curdate = date("Y-m-d");
  mysql_query("DELETE FROM gigs WHERE gigs.date < DATE_SUB('$curdate', INTERVAL 1 DAY)");
}
function emailreminders() {
  date_default_timezone_set('America/Los_Angeles');
  $tomorrow = mktime(0,0,0,date("n"),date("j")+1,date("Y"));
  $tomorrowstr = date("Y-m-d", $tomorrow);
  $gigs = mysql_query("SELECT name, loadtime, starttime, endtime, location, comments, gigid FROM gigs WHERE date = '$tomorrowstr'");
  if ($gigs) {
    $gotrow = false;
    $message = "";
    while ($row = mysql_fetch_array($gigs)) {
      if ($gotrow)
	$message = $message . "<br />&nbsp;<br />";
      $gotrow = true;
      $message = $message . $row['name'] . " is tomorrow.<br/>Loading is at " . 
	sqltohumantime($row['loadtime']) . ".<br >We are playing from " . 
	sqltohumantime($row['starttime']) . " to " . sqltohumantime($row['endtime']) . " at " . 
	$row['location'] . ".";
      if ($row['comments'] != "") {
	$message = $message . "<br />&nbsp;<br />Comments:";
	$message = $message . "<br />" . $row['comments'];
      }
      $recsongs = get_recommended_songs($row['gigid']);
      if ($recsongs != "") {
	$message = $message . "<br />&nbsp;<br />Recommended songs:<br />&nbsp;<br />";
	$message = $message . $recsongs . "<br />";
      }
    }
    if ($gotrow)
      //send_to_members("emailreminder = 1", "Gig Reminder", $message);
      emailtucker("Gig Reminder", $message);
  }
}

emailreminders();
deleteoldgigs();

echo mysql_error();
mysql_close($con);
send_email("Tucker", "tuckerl@stanford.edu", "Email Reminders", "they're working");
echo "Done.<br/>";
?>
