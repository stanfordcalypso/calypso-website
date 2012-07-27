<tr><td colspan='2'>
<?php
   if (!$firstvisit)
     echo "<h1>Settings</h1>";
?><br />
<?php
$firsttime = true;
$mname = "";
$memail = "";
$memailnew = 1;
$memailconfirm = 1;
$memailreminder = 1;
$result = mysql_query("SELECT name, email, emailnew, emailconfirm, emailreminder FROM members WHERE sunetid = '$SUNETID'");
if ($row = mysql_fetch_array($result)) {
  $firsttime = false;
  $mname = $row['name'];
  $memail = $row['email'];
  $memailnew = $row['emailnew'];
  $memailconfirm = $row['emailconfirm'];
  $memailreminder = $row['emailreminder'];
}

?>
<div id="settingsdiv">
   <table style='width:250px'>
   <tr><td style='width:100px'>Name:</td><td style='width:150px'><input id="name" value="<?php echo $mname; ?>" /></td></tr>
   <tr><td style='width:100px'>Email:</td><td style='width:150px'><input id="email" value="<?php echo $memail; ?>" /></td></tr>
   </table>
   <table>
  <tr><td>I want an email when a new gig is posted: <input type="checkbox" <?php if ($memailnew == 1){echo "checked='checked ' ";} ?>id="newe" /></td></tr>
   <tr><td>I want an email when a gig is confirmed: <input type="checkbox" <?php if ($memailconfirm == 1){echo "checked='checked ' ";} ?>id="confirme" /></td></tr>
   <tr><td>I want an email reminder before each gig: <input type="checkbox" <?php if ($memailreminder == 1){echo "checked='checked ' ";} ?>id="reminde" /> </td></tr>
   </table>
<br />
<input type="button" value="<?php
if ($firsttime)
echo "Sign Up";
else
echo "Edit Settings";
?>" onclick="editsettings()" style='position:relative;left:50px'>

</div>

<script type="text/javascript">
function editsettings() {
  var name = id("name").value;
  var email = id("email").value;
  var newe = id("newe").checked ? 1 : 0;
  var confirme = id("confirme").checked ? 1 : 0;
  var reminde = id("reminde").checked ? 1 : 0;
  var sendstr = "setsettings&id=<?php echo $SUNETID; ?>&name="+name+"&email="+email+"&emailnew="+newe+"&emailconfirm="+confirme+"&emailreminder="+reminde;
  dopostajax(sendstr, goteditresponse);
  id("settingsdiv").innerHTML = "Processing...";
}

function goteditresponse(x) {
<?php
  if ($firstvisit)
    echo "window.location=window.location;";
  else
    echo "id('settingsdiv').innerHTML = x;";
?>
}
</script>

</td></tr>