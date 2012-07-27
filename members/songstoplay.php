<tr>
<td colspan='2'>
<h1>Recommended songs for <?php
$gigid = $_GET['gigid'];

$result = mysql_query("SELECT name FROM gigs WHERE gigid = '$gigid'");
if ($row = mysql_fetch_array($result))
  echo $row['name'];


?></h1>
<br />
<?php

$result = mysql_query("SELECT members.name FROM responses, members WHERE responses.gigid = '$gigid' AND responses.sunetid = members.sunetid AND responses.playing = '1'");
$count = mysql_num_rows($result);
if ($count > 0) {
echo "People playing: ";
$i = 0;
while ($row = mysql_fetch_array($result)) {
  echo $row['name'];
  if ($i++ < $count - 1)
    echo ", ";
}
}
else {
  echo "No one has signed up to play yet.";
  exit();
}


?>

&nbsp;<br />&nbsp;<br />
<div id="recommendations">
<?php
include "../recommendedsongs.php";
echo get_recommended_songs($_GET['gigid']);
?>
</div>

</td></tr>




