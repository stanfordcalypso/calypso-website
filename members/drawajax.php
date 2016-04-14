<?php
//include "connect.php";
//include "../dbcon.php";
include(dirname(__FILE__) . "/../dbcon.php");

function echorow($a) {
  $smalldivider='^';
  $bigdivider='%';

    $count = count($a);
    for ($i = 0; $i < $count; $i++) {
        echo $a[$i];
        if ($i < $count - 1)
            echo $smalldivider;
    }
    
    echo $bigdivider;
}

function echoresult($r) {
    while ($row = mysql_fetch_array($r)) {
        echorow($row);
    }
}

if ($_GET['action'] == 'add') {
  $result = mysql_query("SELECT id FROM draw");
  if (mysql_num_rows($result) > 20) {
    $keepgoing = true;
    while ($keepgoing) {
      $keepgoing = false;
      $first = mysql_fetch_array(mysql_query("SELECT MIN(time) as mtime FROM draw"));
      echo $first['mtime'];
      mysql_query("DELETE FROM draw WHERE time = '$first[mtime]'");
      $result = mysql_query("SELECT id FROM draw");
      if (mysql_num_rows($result) > 15)
	$keepgoing = true;
    }
  }

  mysql_query("INSERT INTO draw SET color = '$_GET[color]', x = '$_GET[x]', y = '$_GET[y]', sunetid='$SUNETID'");
}
else {
  $oldtimephp = mktime(date("H"), date("i")-1, date("s"), date("n"), date("j"), date("Y"));
  $oldtime = date('Y', $oldtimephp) . "-" . date('m', $oldtimephp) . "-" . date('d', $oldtimephp) . " " . date('H', $oldtimephp) . ":" . date('i', $oldtimephp) . ":" . date('s', $oldtimephp);

  $result = mysql_query("SELECT id FROM draw WHERE time < '$oldtime'");
  if (mysql_fetch_array($result)) {
    mysql_query("DELETE FROM draw WHERE time < '$oldtime'");
    $mt = mysql_query("SELECT MIN(time) as mtime FROM draw");
    if ($mtr = mysql_fetch_array($mt))
      echo $mtr["mtime"] . "%";
    else 
      echo "%";
    echo "deleted%";
    $result = mysql_query("SELECT color, x, y FROM draw ORDER BY id");
    echoresult($result);
  }
  else {
    $mt = mysql_query("SELECT MIN(time) as mtime FROM draw");
    if ($mtr = mysql_fetch_array($mt))
      echo $mtr["mtime"] . "%";
    else
      echo "%";

    $first = $_GET['first'];
    $lastupdate = $_GET['lastupdate'];
    if ($first == "" || $first < $mtr["mtime"]) {
      echo "deleted%";
      $result = mysql_query("SELECT color, x, y FROM draw ORDER BY id");
    }
    else
      $result = mysql_query("SELECT color, x, y FROM draw WHERE time > '$lastupdate' ORDER BY id");
    echoresult($result);
  }
}

mysql_close($con);

?>
