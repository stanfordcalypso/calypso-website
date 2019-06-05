<tr><td colspan='2'>
<?php

   if (!$firstvisit)
     echo "<h1>Settings</h1>";
?><br />
<?php
$firsttime = true;
$mname = "";
$memail = "";
$mphonenumber = "";
$mphonecarrier = "";
$memailnew = 1;
$memailconfirm = 1;
$memailreminder = 1;
$mtextreminder = 1;
$result = mysql_query("SELECT name, email, phonenumber, phonecarrier, emailnew, emailconfirm, emailreminder, textreminder FROM members WHERE sunetid = '$SUNETID'");
if ($row = mysql_fetch_array($result)) {
  $firsttime = false;
  $mname = $row['name'];
  $memail = $row['email'];
  $mphonenumber = $row['phonenumber'];
  $mphonecarrier = $row['phonecarrier'];
  $memailnew = $row['emailnew'];
  $memailconfirm = $row['emailconfirm'];
  $memailreminder = $row['emailreminder'];
  $mtextreminder = $row['textreminder'];
}

?>
<div id="settingsdiv">
   <table style='width:280px'>
   <tr><td style='width:130px'>Name:</td><td style='width:150px'><input id="name" value="<?php echo $mname; ?>" /></td></tr>
   <tr><td style='width:130px'>Email:</td><td style='width:150px'><input id="email" value="<?php echo $memail; ?>" /></td></tr>
   <tr><td style='width:130px'>Phone number:</td><td style='width:150px'><input id="phonenumber" maxlength="10" value="<?php echo $mphonenumber; ?>" /></td></tr>

   <!-- dropdown list of cell carriers, where default selected option is their previous carrier choice -->
   <?php $carriers = array('ATT' => 'AT&T','Verizon'=>'Verizon', 'Sprint'=>'Sprint', 'T-Mobile'=>'T-Mobile', 'Consumer Cellular'=>'Consumer Cellular', 'Boost'=>'Boost','Metro PCS'=>'Metro PCS'); ?>
   <tr><td style='width:130px'>Phone carrier:</td>
     <td style='width:150px'><select id="phonecarrier">
       <option value="NULL" selected="selected" disabled>Select a carrier...</option>
     <?php foreach ($carriers as $id=> $value) { ?>
       <option value="<?php echo $id;?>" <?php echo ($id==  $mphonecarrier) ? ' selected="selected"' : '';?>><?php echo $value;?></option>
     <?php } ?>
   </select></td></tr>

   </table>
   <table>

   <tr><td>I want an email when a new gig is posted: <input type="checkbox" <?php if ($memailnew == 1){echo "checked='checked ' ";} ?>id="newe" /></td></tr>
   <tr><td>I want an email when a gig is confirmed: <input type="checkbox" <?php if ($memailconfirm == 1){echo "checked='checked ' ";} ?>id="confirme" /></td></tr>
   <!-- Old email reminder system; abandoned because a continuously running cron job (for reminders) was too complicated
       <tr><td>I want an email reminder before each gig: <input type="checkbox" <?php if ($memailreminder == 1){echo "checked='checked ' ";} ?>id="reminde" /> </td></tr>
     -->
   <!-- Most recent attempt at SMS reminders; abandonded in favor of Slack
       <tr><td>I want a text reminder before each gig: <input type="checkbox" <?php if ($mtextreminder == 1){echo "checked='checked ' ";} ?>id="remindtext" /> </td></tr>
     -->
   </table>
<br />
<input type="button" value="<?php
if ($firsttime)
echo "Sign Up";
else
echo "Update Settings";
?>" onclick="editsettings()" style='position:relative;left:50px'>

</div>

<script type="text/javascript">
function editsettings() {
  var name = id("name").value;
  var email = id("email").value;
  var phonenumber = id("phonenumber").value;
  var phonecarrier = id("phonecarrier").value;
  var newe = id("newe").checked ? 1 : 0;
  var confirme = id("confirme").checked ? 1 : 0;
  // var reminde = id("reminde").checked ? 1 : 0;
  var reminde = 1;
  // var remindtext = id("remindtext").checked ? 1 : 0;
  var remindtext = 1;
  var sendstr = "setsettings&id=<?php echo $SUNETID; ?>&name="+name+"&email="+email+"&phonenumber="+phonenumber+"&phonecarrier="+phonecarrier+"&emailnew="+newe+"&emailconfirm="+confirme+"&emailreminder="+reminde+"&textreminder="+remindtext;

  //mail('zacharyb@stanford.edu', 'Testing text', 'Hi Zachary');
  //text_to_members("textreminder = 1", "Testing text", "Hi Zachary");
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
