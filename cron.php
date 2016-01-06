<?php

  /*
   * This file is used for the automated gig reminder system.
   * The Firefox and Chrome plugins under members/ will request
   * this page every few minutes, so that shedule_function can
   * be used to set "cron" jobs at the same hour each day.
   */

echo "-2<br/>";
include "dbcon.php";
include "email.php";
//include "members/twiliotest.php";
echo "-1<br/>";
include "members/sms.php";

function sqltohumantime($t) {
  list($hour, $min, $sec) = split(':', $t);
  $time = mktime($hour, $min, $sec);
  return date('g', $time) . ":" . date('i', $time) . " " . date('A', $time);
}

$schedule_count = 0;
$schedule_arr = array();

echo "0<br/>";
//$gigremindertime = "17:00";
$gigremindertime = "00:00";
class FirstPractice {
  //public static $time = "15:30";
  //public static $day = "Sunday";
  public static $day = "NEVAR";
  public static $time = "00:00";
}
class SecondPractice {
  //public static $time = "19:30";
  //public static $day = "Thursday";
  public static $day = "Wednesday";
  public static $time = "00:00";
}
//$firstpracticeremindertime = "15:30";
//$secondpracticeremindertime = "19:30";

class Job {
  public $time;
  public $func;
  public $jobid;
}

function schedule_function($time, $func) {
  global $schedule_count;
  global $schedule_arr;
  $job = new Job;
  $job->time = $time;
  $job->func = $func;
  $job->jobid = $schedule_count;
  $schedule_arr[$schedule_count] = $job;
  $schedule_count++;
}

//$con = mysql_connect("mysql-user.stanford.edu","gcalypsomember0","iegoobei");
//if (!$con) {
   //die('Could not connect: ' . mysql_error());
//}

function emailjesse() {
  send_email("Jesse", "jruder@stanford.edu", "cron test", "this is a test");
}

function emailtucker() {
  send_email("Tucker", "tuckerl@stanford.edu", "cron test", "this is a test");
}

include "recommendedsongs.php";
function emailreminders() {
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
      send_to_members("emailreminder = 1", "Gig Reminder", $message);
  }
}
echo "1<br/>";
function textreminders() {
  $today = date("l");
  echo $today . "<br/>";
  $setup_numbers = array();
  $teardown_numbers = array();
  if ($today == FirstPractice::$day) {
    $setup_numbers = get_cell_nums("0", "0");
    $teardown_numbers = get_cell_nums("0", "1");
  } else if ($today == SecondPractice::$day) {
    $setup_numbers = get_cell_nums("1", "0");
    $teardown_numbers = get_cell_nums("1", "1");
  }
  var_dump($setup_numbers);
  var_dump($teardown_numbers);
  send_texts($setup_numbers, "Reminder: you're on Calypso set-up crew today! Please be at practice in 15 minutes.");
  send_texts($teardown_numbers, "Reminder: you're on Calypso tear-down crew today! Practice starts in 30 minutes.");
}

//schedule_function("2", "emailjesse");
schedule_function($gigremindertime, "emailtucker");
schedule_function(FirstPractice::$time, "textreminders");
schedule_function(SecondPractice::$time, "textreminders");
//schedule_function("17", "emailreminders");

//mysql_select_db("g_calypso_members", $con);

$curday = date("j");
echo $curday . "<br />";
$curtime = strtotime(date("H:i")) ;
echo $curtime . "<br />";
for ($i = 0; $i < $schedule_count; $i++) {
  $jobid = $schedule_arr[$i]->jobid;
  if ($curtime >= strtotime($schedule_arr[$i]->time) && mysql_query("INSERT INTO cron SET jobid='$jobid', day='$curday'")) {
    echo "Executing job <br/>";
    mysql_query("DELETE FROM cron WHERE jobid='$jobid' AND day<>'$curday'");
    call_user_func($schedule_arr[$i]->func);
  }
}

//mysql_query("INSERT INTO jessetest SET name = 'blah'");
//mysql_query("INSERT INTO cron SET sunetid = '$SUNETID'");
echo mysql_error();

mysql_close($con);

send_email("Tucker", "tuckerl@stanford.edu", "cron test", "this is a test");
?>
