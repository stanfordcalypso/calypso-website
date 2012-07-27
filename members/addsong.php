<tr><td class='half'>
<h1><?php
$newsong = 1;
$songid = 0;
$songname = "";
$songarranger = "";
$songoriginal = "";
$songscore = "";
$songmidi = "";

if (isset($_GET['songid'])) {
  $newsong = 0;
  $songid = $_GET['songid'];
  echo "Edit Song";

  $result = mysql_query("SELECT name, arranger, original, score, midi FROM songs WHERE songid = '$songid'");
  if ($row = mysql_fetch_array($result)) {
    $songname = " value='" . $row['name'] . "'";
    $songarranger = $row['arranger'];
    $songoriginal = " value='" . $row['original'] . "'";
    $songscore = " value='" . $row['score'] . "'";
    $songmidi = " value='" . $row['midi'] . "'";
  }
}
else {
  echo "New Song";
}

?></h1>
<br />
<div id="addsongdiv">
<table style='width:300px'>
  <tr><td><div style='width:130px'>Name:</div></td><td><input id="songname" size="35"<?php echo $songname; ?>></td></tr>
<tr><td><div style='width:130px'>Arranged by:</div></td><td><?php
  $songarranger;

echo "<select id='arranger'>";
$result = mysql_query("SELECT name, sunetid FROM members ORDER BY name");
while ($row = mysql_fetch_array($result)) {
  echo "<option value='" . $row['sunetid'] . "'";
  if (($songarranger == "" && $row['sunetid'] == $SUNETID) || $songarranger == $row['sunetid'])
    echo " selected='selected'";
  echo ">" . $row['name'] . "</option>";
}
echo "</select>";

?></td></tr>
<tr><td><div style='width:130px'>Link to original:</div></td><td><input id="original" size="35"<?php echo $songoriginal; ?>></td></tr>
<tr><td><div style='width:130px'>PDF score:</div></td><td><input id="score" size="35"<?php echo $songscore; ?>></td></tr>
<tr><td><div style='width:130px'>MIDI file:</div></td><td><input id="midi" size="35"<?php echo $songmidi; ?>></td></tr>
</table>

<br />

<input type="button" value="<?php if ($newsong == 1){echo "Add";}else{echo "Submit Changes";} ?>" onclick="submitsong()">

<?php
  if ($newsong == 0) {
    echo "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='button' value='Delete Song' onclick='deletesong()'>";
  }
?>

</div>

</td><td class='half'>
<div id="uploadfilediv">
<h1>Upload New Files</h1>
<br />
<div style='font-size:80%'>Note: Please host files somewhere else if possible. We're running out of room...</div><br />
<iframe src="uploadfiles.php" frameBorder="0" style="border:0px while solid"></iframe>
</div>

<script type="text/javascript">
function submitsong() {
   var name = id("songname").value;
   var arranger = id("arranger").value;
   var original = id("original").value;
   var score = id("score").value;
   var midi = id("midi").value;
<?php
if ($newsong == 1) {
   echo "var sendstr = 'addsong&name=' + name + '&arranger=' + arranger + '&original=' + original + '&score=' + score + '&midi=' + midi;";
}
else {
   echo "var sendstr = 'editsong&songid=" . $songid . "&name=' + name + '&arranger=' + arranger + '&original=' + original + '&score=' + score + '&midi=' + midi;";
}

?>

   dopostajax(sendstr, gotsubmission);
   id("addsongdiv").innerHTML = "Processing...";
   id("uploadfilediv").innerHTML = "";
}

function deletesong() {
if (confirm("Are you sure? This will delete the song permanently, along with all parts associated with it.")) {
   var sendstr = 'deletesong&songid=<?php echo $songid; ?>';
   dopostajax(sendstr, gotsubmission);
   id("addsongdiv").innerHTML = "Processing...";
   id("uploadfilediv").innerHTML = "";
}
}

function gotsubmission(x) {
   id("addsongdiv").innerHTML = x;
}
</script>

</td></tr>