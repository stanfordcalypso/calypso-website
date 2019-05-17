<tr><td colspan=2>
<h1>Gig Response</h1><br />
<div id="gigresponsediv">
<?php
if (!isset($_GET[gigid]) || empty($_GET[gigid])) {
   echo "Error: Please return to profile and try again";
   exit();
}

$gigid = $_GET[gigid];

$isloading = 1;
$isplaying = 1;
$iscleanup = 1;
$iscar = 0;
$comments = "";
$result = mysql_query("SELECT loading, playing, cleanup, car, comments FROM responses WHERE gigid = '$gigid' AND sunetid = '$SUNETID'");
if ($row = mysql_fetch_array($result)) {
   $isloading = $row['loading'];
   $isplaying = $row['playing'];
   $iscleanup = $row['cleanup'];
   $iscar = $row['car'];
   $comments = $row['comments'];
}

echo "Can you come to ";

$result = mysql_query("SELECT * FROM gigs WHERE gigid = '$gigid'");
$row = mysql_fetch_array($result);
echo $row['name'] . " on <span id='dateandtime'></span>";
echo "&nbsp;<br />&nbsp;<br/>";

echo "<table style='width:400px'><tr><td>";
echo "I'll be there for:</br>";
echo "<table style='width:150px'><tr><td>Loading: </td><td><input type='checkbox' ";
if ($isloading == 1) echo "checked='true' ";
echo "id='gigload'></td></tr>";
echo "<tr><td>Playing: </td><td><input type='checkbox' ";
if ($isplaying == 1) echo "checked='true' ";
echo "id='gigplay'></td></tr>";
echo "<tr><td>Clean up: </td><td><input type='checkbox' ";
if ($iscleanup == 1) echo "checked='true' ";
echo "id='gigclean'></td></tr>";
echo "</td></tr></table></td><td>";
echo "<div style='width:200px'><table style='width:150px'><tr><td>";
echo "I can bring a car: </td><td>";
echo "<input type='checkbox' ";
if ($iscar == 1) echo "checked=true ";
echo "id='gigcar'></td></tr>";
echo "<tr><td>Comments:</td></tr><tr><td><textarea cols=20 rows=2 id='gigcomments'>";
echo $comments;
echo "</textarea></td></tr>";
echo "</table></div>";
echo "</td></tr>";
echo "<tr><td colspan=2>&nbsp;<br /><center><input type='button' value='Submit' onClick='gigresponsesubmit()'></center></td></tr></table>";

?>
</div>

<script type="text/javascript">
var txt = printdate("<?php echo $row['date']; ?>") + "?<br />";
txt += "Loading starts at " + printtime("<?php echo $row['loadtime']; ?>") + ".<br />";
txt += "We are playing from " + printtime("<?php echo $row['starttime']; ?>") + " to " + printtime("<?php echo $row['endtime']; ?>") + ".<br />";
var additionalComments = "<?php echo $row['comments']; ?>";
if (additionalComments != "") {
  txt += "Additional comments: " + additionalComments + "<br />";
}
id("dateandtime").innerHTML = txt;

function gigresponsesubmit() {
  var isloading = id("gigload").checked ? 1 : 0;
  var isplaying = id("gigplay").checked ? 1 : 0;
  var iscleanup = id("gigclean").checked ? 1 : 0;
  var iscar = id("gigcar").checked ? 1 : 0;
  var comments = id("gigcomments").value.replace("%", " percent");
  var sendstr = "gigresponse&id=<?php echo $SUNETID ?>&gigid=<?php echo $_GET[gigid] ?>&loading=" + isloading + "&playing=" + isplaying + "&cleanup=" + iscleanup + "&car=" + iscar + "&comments=" + comments;
  dopostajax(sendstr, gotgigresponse);
  id("gigresponsediv").innerHTML = "Processing...";
}

function gotgigresponse(x) {
   id("gigresponsediv").innerHTML = x;
}
</script>


</td></tr>
