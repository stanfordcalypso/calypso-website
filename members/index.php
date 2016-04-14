<?php

//include "connect.php";
//include "../dbcon.php";
include(dirname(__FILE__) . "/../dbcon.php");

$action = "profile";
if (isset($_GET[action]) && !empty($_GET[action])) {
   $action = $_GET[action];
}
else if (isset($_POST[action]) && !empty($_POST[action])) {
   $action = $_POST[action];
}
?>

<html>
<head>
  <title>Cardinal Calypso</title>
  <link rel='stylesheet' type='text/css' href='css.css' />
  <style type='text/css'>
    input {border:2px solid #ccc};
    select {border:2px solid #ccc};
  </style>
<script type="text/javascript" src="jessejs.js"></script>
<!-- <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script> -->
</head>
<body><div align='center'><table id='wrapper' cellspacing='0' cellpadding='0'>
<!--
<script type="text/javascript">
	// Enter a client ID for a web application from the Google Developer Console.
      // The provided clientId will only work if the sample is run directly from
      // https://google-api-javascript-client.googlecode.com/hg/samples/authSample.html
      // In your Developer Console project, add a JavaScript origin that corresponds to the domain
      // where you will be running the script.
      var clientId = '1072266037494';

      // Enter the API key from the Google Develoepr Console - to handle any unauthenticated
      // requests in the code.
      // The provided key works for this sample only when run from
      // https://google-api-javascript-client.googlecode.com/hg/samples/authSample.html
      // To use in your own application, replace this API key with your own.
      var apiKey = 'AIzaSyCGzO_yilbkluevZgy4q_4nOdMxkj7OYkk';

      // To enter one or more authentication scopes, refer to the documentation for the API.
      var scopes = 'https://www.googleapis.com/auth/calendar';

      // Use a button to handle authentication the first time.
      function handleClientLoad() {
        gapi.client.setApiKey(apiKey);
      }
</script>
-->
  <tr>
    <td id='left' colspan='2'>
      <div id='logo'></div>
      <table id='nav' cellspacing='0' cellpadding='0'><tr>
	<td><a href='http://www.stanford.edu/group/calypso/cgi-bin/'>Public Site</a></td>
<?php
$allids = mysql_query("SELECT sunetid, name FROM members");
$firstvisit = true;
$membername = "";
while ($row = mysql_fetch_array($allids)) {
   if ($row['sunetid'] == $SUNETID) {
      $firstvisit = false;
      $membername = $row['name'];
   }
}
$profilestr = "Profile";
if (!$firstvisit) {
   $profilestr = $membername . "'s Profile";
}

	    $actions = array('profile' => $profilestr, 'gigs' => 'Gigs', 'songs' => 'Songs', 'resources' => 'Resources', 'settings' => 'Settings', 'help' => 'Help');
foreach ($actions as $key => $label) {
   echo "<td";
   if ($action == $key) echo " id='here'";
   echo "><a href='?action={$key}'>{$label}</a></td>";
}
?>
</tr></table>
    </td> 
  </tr><tr>
    <td id='content'><table cellspacing='0' cellpadding='0' style='height:400px'>

<tr><td colspan='2' style='height:10px'>
<?php
if ($firstvisit) {
  $action = "settings";
  echo "Hello! Enter your information below:<br />(you can click on \"Settings\" to edit it later)<br/>";
  /*
   echo "<script type='text/javascript'>";
   echo "function doneadding(x) {window.location=window.location;};";
   //;id('firstvisit').innerHTML='Thank you! Now you can sign up for gigs and fill in which songs you play.';resetajax();};";
   echo "function newmember() {var name=id('newname').value;doajax('addmember&id=";
   echo $SUNETID;
   echo "&name='+name,doneadding);};";
   echo "</script>";
   echo "<div id='firstvisit'>Hello " . $SUNETID . ". It looks like you haven't entered your information yet. Please enter your name:<br/><input id='newname' /> <input type='button' value='Sign Up' onclick='newmember()'></div>";
  */
}
/*
else {
   echo "Logged in as " . $membername;
}*/

?>
</td></tr>

<?php
//echo "<center>Site is temporarily down because we are out of room on AFS and I don't want the database getting messed up.</center>";
//exit();


if ($action == "profile") {
    include "profile.php";
}
else if ($action == "respond") {
    include "respond.php";
}
else if ($action == "gigs") {
    include "gigs.php";
}
else if ($action == "addgig") {
    include "addgig.php";
}
else if ($action == "songs") {
    include "songs.php";
}
else if ($action == "addsong" || $action == "editsong") {
    include "addsong.php";
}
else if ($action == "songstoplay") {
  include "songstoplay.php";
}
else if ($action == "editgig") {
  include "editgig.php";
}
else if ($action == "settings") {
  include "settings.php";
}
else if ($action == "draw") {
  include "draw.php";
}
else if ($action == "help") {
  include "help.php";
}
else if ($action == "resources") {
  include "resources.php";
}
else if ($action == "twiliotest") {
  include "twiliotest.php";
}
else if ($action == "test") {
  include "test.php";
}
mysql_close($con);

?>


</table>
</td>

  </tr>
  <tr id='foot'><td colspan='2'>Copyright &copy;2006-<?=date('Y')?> Cardinal Calypso &middot; <a href='http://assu.stanford.edu' target='_blank'>ASSU</a> &middot; <a href='http://www.facebook.com/group.php?gid=2200276540' target='_blank'>FB</a></td></tr>
</table></div></body>
</html>
