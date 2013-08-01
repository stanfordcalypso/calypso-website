<?php
$smalldivider = "^";
$bigdivider = "%";

$action = "none";
if (isset($_GET[action]) && !empty($_GET[action]))
   $action = $_GET[action];
else if (isset($_POST[action]) && !empty($_POST[action]))
   $action = $_POST[action];


$input = array();

include "connect.php";

foreach ($_POST as $key => $value)
  $input[$key] = mysql_real_escape_string($value);

foreach ($_GET as $key => $value)
  $input[$key] = mysql_real_escape_string($value);

foreach ($input as $key => $value) {
    if ($value == "true")
        $input[$key] = '1';
    if ($value == "false")
        $input[$key] = '0';
}

function echorow($a) {
    global $smalldivider;
    global $bigdivider;

    $count = count($a);
    for ($i = 0; $i < $count; $i++) {
        echo stripslashes($a[$i]);
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

function haveinput($v) {
    global $input;
    if (isset($input[$v]))
        return true;
    else
        return false;
}

function deleteoldgigs() {
  $curdate = date("Y-m-d");
  mysql_query("DELETE FROM gigs WHERE gigs.date < '$curdate'");
}

include "../email.php";

if ($action != "none") {
if ($action == "listall") {
   $result = mysql_query("SELECT sunetid, name, email, emailnew, emailconfirm, emailreminder FROM members");
   echoresult($result);
}
else if ($action == "songs") {
   $result = mysql_query("SELECT songid, name FROM songs ORDER BY name");
   echoresult($result);
}
else if ($action == "instruments") {
   $result = mysql_query("SELECT instrumentid, name FROM instruments ORDER BY instrumentid");
   echoresult($result);
}
else if ($action == "skills") {
   $result = mysql_query("SELECT skillid, name FROM skills ORDER BY skillid");
   echoresult($result);
}
else if ($action == "addmember") {
   if (haveinput('name') && haveinput('id')) {
       mysql_query("INSERT INTO members (sunetid, name)
VALUES
('$input[id]','$input[name]')");
   }
}
else if ($action == "addnewpart") {
   if (haveinput('id') && haveinput('song') && haveinput('instrument') && haveinput('skill') && $input[skill] != 0) {
       mysql_query("REPLACE INTO parts (sunetid, songid, instrumentid, skillid)
VALUES
('$input[id]','$input[song]','$input[instrument]','$input[skill]')");

	echo mysql_error();
   }
}
else if ($action == "partsbysong") {
   if (haveinput('id')) {
      $result = mysql_query("SELECT songs.name AS song, instruments.name AS inst, parts.skillid, parts.songid, parts.instrumentid AS skill FROM parts, songs, instruments WHERE parts.sunetid = '$input[id]' AND parts.songid = songs.songid AND parts.instrumentid = instruments.instrumentid ORDER BY songs.name, instruments.instrumentid");

      echoresult($result);
   }
}
else if ($action == "partsbyinst") {
   if (haveinput('id')) {
      $result = mysql_query("SELECT instruments.name AS inst, songs.name AS song, parts.skillid, parts.songid, parts.instrumentid  AS skill FROM parts, songs, instruments WHERE parts.sunetid = '$input[id]' AND parts.songid = songs.songid AND parts.instrumentid = instruments.instrumentid ORDER BY instruments.instrumentid, songs.name");

      echoresult($result);
   }
}
else if ($action == "responseneeded") {
   if (haveinput('id')) {
     deleteoldgigs();
      $result = mysql_query("SELECT name, date, starttime, gigid FROM gigs WHERE NOT EXISTS (SELECT * FROM responses WHERE responses.sunetid = '$input[id]' AND responses.gigid = gigs.gigid) ORDER BY gigs.date, gigs.gigid");

      echoresult($result);
   }
}
else if ($action == "gigresponse") {
    if (haveinput('id') && haveinput('gigid') && haveinput('loading') && haveinput('playing') && haveinput('cleanup') && haveinput('car') && isset($input[comments])) {
        if (mysql_query("REPLACE INTO responses (sunetid, gigid, loading, playing, cleanup, car, comments)
        VALUES
        ('$input[id]','$input[gigid]','$input[loading]','$input[playing]','$input[cleanup]','$input[car]','$input[comments]')")) {
            echo "Your response was recorded successfully!<br />&nbsp;<br /><a href='?action=profile'>Back to profile</a>";
        }
        else {
            echo "Error: Please try again";
        }
    }
    else {
        echo "Error: Please try again";
    }
}
else if ($action == "allgigs") {
  deleteoldgigs();
    $result = mysql_query("SELECT gigid, name, comments, date, loadtime, starttime, endtime, location, confirmed, creator FROM gigs ORDER BY date, gigid");
    echoresult($result);
}
else if ($action == "singlegig") {
    if (haveinput('gigid')) {
        $result = mysql_query("SELECT gigid, name, comments, date, loadtime, starttime, endtime, location, confirmed, creator, attire FROM gigs WHERE gigid = '$input[gigid]'");
        echoresult($result);
    }
}
else if ($action == "gigresponses") {
    if (haveinput('gigid')) {
        $result = mysql_query("SELECT members.sunetid, members.name, responses.loading, responses.playing, responses.cleanup, responses.car, responses.comments FROM responses, members WHERE responses.gigid = '$input[gigid]' AND responses.sunetid = members.sunetid ORDER BY members.name");
        echoresult($result);
    }
}
else if ($action == "savegig") {
  deleteoldgigs();
    if (haveinput('gigid') && haveinput('name') && haveinput('date') && haveinput('loadtime') && haveinput('starttime') && haveinput('endtime') && haveinput('location') && haveinput('confirmed') && haveinput('comments')) {
      $curdate = date("Y-m-d");
      if ($input['date'] < $curdate) {
	echo "Error: Date has already passed!";
      }
      else {
        mysql_query("REPLACE INTO gigs (gigid, name, comments, date, loadtime, starttime, endtime, location, confirmed)
        VALUES
        ('$input[gigid]','$input[name]','$input[comments]','$input[date]','$input[loadtime]','$input[starttime]','$input[endtime]','$input[location]','$input[confirmed]')");
      }
    }
}
else if ($action == "addgig") {
  deleteoldgigs();
    if (haveinput('name') && haveinput('date') && haveinput('loadtime') && haveinput('starttime') && haveinput('endtime') && haveinput('location') && haveinput('confirmed') && haveinput('comments') && haveinput('attire')) {
      $curdate = date("Y-m-d");
      if ($input['date'] < $curdate) {
	echo "Error: Date has already passed!";
      }
      else if (mysql_query("INSERT INTO gigs (name, comments, date, loadtime, starttime, endtime, location, confirmed, attire)
        VALUES
        ('$input[name]','$input[comments]','$input[date]','$input[loadtime]','$input[starttime]','$input[endtime]','$input[location]','$input[confirmed]','$input[attire]')")) {
	$result = mysql_query("SELECT gigid FROM gigs WHERE name = '$input[name]' AND date = '$input[date]' AND starttime = '$input[starttime]'");
	if ($result && $row = mysql_fetch_array($result)) {
	  $url = "https://www.stanford.edu/group/calypso/cgi-bin/members/?action=respond&gigid=" . $row['gigid'];
	  send_to_members("emailnew = 1", "New Gig: " . $input['name'],
"Click here to respond to this gig:<br />
<a href='" . $url . "'>" . $url . "</a>");
	  echo "New gig emails sent.<br />&nbsp;<br />";
	}
	echo "Gig added successfully!<br />&nbsp;<br /><a href='?action=gigs'>Back to gigs</a>";
      }
      else {
            echo "Error: Please try again";
      }
    }
}
else if ($action == "editgig") {
  deleteoldgigs();
  if (haveinput("gigid") && haveinput('name') && haveinput('date') && haveinput('loadtime') && haveinput('starttime') && haveinput('endtime') && haveinput('location') && haveinput('confirmed') && haveinput('comments') && haveinput('attire')) {
    $curdate = date("Y-m-d");
    if ($input['date'] < $curdate) {
      echo "Error: Date has already passed!";
    }
    else {
      $origconfirmed = 1;
      $original = mysql_query("SELECT confirmed FROM gigs WHERE gigid = '$input[gigid]'");
      if ($row = mysql_fetch_array($original)) {
	$origconfirmed = $row['confirmed'];
      }
      
      if (mysql_query("REPLACE INTO gigs (gigid, name, comments, date, loadtime, starttime, endtime, location, confirmed, attire)
        VALUES
        ('$input[gigid]','$input[name]','$input[comments]','$input[date]','$input[loadtime]','$input[starttime]','$input[endtime]','$input[location]','$input[confirmed]','$input[attire]')")) {
	if ($origconfirmed == 0 && $input['confirmed'] == 1) {
	  send_to_members("emailconfirm = 1", "Gig Confirmation: " . $input['name'], $input['name'] . " has been confirmed!");
	  echo "Confirmation emails sent.<br />&nbsp;<br />";
	}
	
	echo "Gig edited successfully!<br />&nbsp;<br /><a href='?action=gigs'>Back to gigs</a>";
      }
      else {
	echo "Error: Please try again";
      }
    }
  }
}
else if ($action == "deletegig") {
  deleteoldgigs();
  if (haveinput('gigid')) {
    mysql_query("DELETE FROM gigs WHERE gigid = '$input[gigid]'");
    mysql_query("DELETE FROM responses WHERE gigid = '$input[gigid]'");
    echo "Deleted gig successfully.<br />&nbsp;<br /><a href='?action=gigs'>Back to gigs</a>";
  }
}
else if ($action == "upcominggigs") {
  deleteoldgigs();
    if (haveinput('id')) {
        $result = mysql_query("SELECT gigs.name, gigs.date, gigs.starttime, gigs.gigid, responses.loading, responses.playing, responses.cleanup, responses.car, gigs.loadtime, responses.comments FROM gigs, responses WHERE responses.sunetid = '$input[id]' AND responses.gigid = gigs.gigid ORDER BY gigs.date, gigs.starttime");
      
        echoresult($result);
    }
}
else if ($action == "allsongs") {
    $result = mysql_query("SELECT songs.songid, songs.name, members.name, songs.original, songs.score, songs.midi FROM songs, members WHERE songs.arranger = members.sunetid ORDER BY songs.name");
    echoresult($result);
}
else if ($action == "singlesong") {
    if (haveinput('songid')) {
        $result = mysql_query("SELECT songs.songid, songs.name, members.name, songs.original, songs.score, songs.midi FROM songs, members WHERE songid = '$input[songid]' AND songs.arranger = members.sunetid");
        echoresult($result);
    }
}
else if ($action == "addsong") {
    if (haveinput('name') && haveinput('arranger') && haveinput('original') && haveinput('score') && haveinput('midi')) {
        if (mysql_query("INSERT INTO songs (name, arranger, original, score, midi)
        VALUES
        ('$input[name]','$input[arranger]','$input[original]','$input[score]','$input[midi]')")) {
            echo "Song added successfully!<br />&nbsp;<br /><a href='?action=songs'>Back to songs</a>";
        }
        else {
            echo "Error: Please try again";
        }
    }
    else {
        echo "Error: Please try again";
    }
}
else if ($action == "editsong") {
  if (haveinput('songid') && haveinput('name') && haveinput('arranger') && haveinput('original') && haveinput('score') && haveinput('midi')) {
        if (mysql_query("REPLACE INTO songs (songid, name, arranger, original, score, midi)
        VALUES
        ('$input[songid]','$input[name]','$input[arranger]','$input[original]','$input[score]','$input[midi]')")) {
            echo "Song edited successfully!<br />&nbsp;<br /><a href='?action=songs'>Back to songs</a>";
        }
        else {
            echo "Error: Please try again";
        }
    }
    else {
        echo "Error: Please try again";
    }
}
else if ($action == "deletesong") {
  if (haveinput('songid')) {
    mysql_query("DELETE FROM songs WHERE songid = '$input[songid]'");
    mysql_query("DELETE FROM parts WHERE songid = '$input[songid]'");
    echo "Deleted song successfully.<br />&nbsp;<br /><a href='?action=songs'>Back to songs</a>";
  }
}
else if ($action == "partsforsong") {
  if (haveinput('songid')) {
    $result = mysql_query("SELECT instruments.name, members.name, parts.skillid FROM parts, instruments, members WHERE parts.songid = '$input[songid]' AND instruments.instrumentid = parts.instrumentid AND members.sunetid = parts.sunetid ORDER BY instruments.instrumentid, members.name");
    echoresult($result);
  }
}
else if ($action == "deletepart") {
  if (haveinput('id') && haveinput('songid') && haveinput('instrumentid')) {
    mysql_query("DELETE FROM parts WHERE sunetid = '$input[id]' AND songid = '$input[songid]' AND instrumentid = '$input[instrumentid]'");
  }
}
else if ($action == "partsforgig") {
  if (haveinput('gigid')) {
    $result = mysql_query("SELECT members.sunetid, members.name, parts.instrumentid, instruments.name, songs.songid, songs.name, parts.skillid
FROM parts, responses, members, songs, instruments
WHERE responses.gigid = '$input[gigid]' AND responses.sunetid = members.sunetid AND responses.playing = '1'
AND parts.sunetid = members.sunetid AND parts.songid = songs.songid AND parts.instrumentid = instruments.instrumentid
ORDER BY songs.songid, instruments.instrumentid, members.name");
    echoresult($result);
  }
}
else if ($action == "setsettings") {
  if (haveinput('id') && haveinput('name') && haveinput('email') && haveinput('emailnew') && haveinput('emailconfirm') && haveinput('emailreminder')) {
    mysql_query("REPLACE INTO members (sunetid, name, email, emailnew, emailconfirm, emailreminder)
VALUES
('$input[id]','$input[name]','$input[email]','$input[emailnew]','$input[emailconfirm]','$input[emailreminder]')");
    echo "Recorded information successfully!<br />&nbsp;<br /><a href='?action=profile'>Go to profile</a>";
  }
}
}

mysql_close($con);
?>