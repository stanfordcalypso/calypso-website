<tr><td colspan=2>
<h1>New Gig</h1>
<br />
<div id="newgigdiv">
<table style='width:600px'>
<tr><td><div style='width:100px'>Name:</div></td><td><input size='20' id='gigname'></td>
<td style='padding-left:20px'><div style='width:100px'>Date:</div></td><td><div id="dateselect" style='width:300px'></span></td></tr>
<tr><td><div style='width:100px'>Load time:</div></td><td><div id="loadtime" style='width:200px'></div></td>
<td style='padding-left:20px'><div style='width:100px'>Start time:</div></td><td><div id="starttime"></div></td></tr>
<tr><td><div style='width:100px'>End time:</div></td><td><div id="endtime"></div></td>
<td style='padding-left:20px'><div style='width:100px'>Location:</div></td><td><input size='20' id='giglocation'></td></tr>

<tr>
	<td colspan=2>Attire:<br /><textarea id='gigattire' row='3' cols='25'></textarea></td>
	<td style='padding-left:20px'><div style='width:100px'>Confirmed:</div></td><td><input type='checkbox' id='gigconfirmed'></td>
</tr>
<tr>
	<td colspan=2>Comments:<br /><textarea id='gigcomments' row='3' cols='25'></textarea></td>
	<td style='padding-left:20px'><div style='width:100px'>Post to Calendar?:</div></td><td><input type='checkbox' id='isInGoogleCalendar'></td>
	
</tr>
</table>

<br />

<input type="button" value="Add" onclick="addgig()">
</div>

<script type="text/javascript">
id("dateselect").innerHTML = dateselect("gigdate");
id("loadtime").innerHTML = timeselect("loadtime");
id("starttime").innerHTML = timeselect("starttime");
id("endtime").innerHTML = timeselect("endtime");

function addgig() {
    var name = id("gigname").value;
    var date = getdate("gigdate");
    var loadtime = gettime("loadtime");
    var starttime = gettime("starttime");
    var endtime = gettime("endtime");
    var location = id("giglocation").value;
    var confirmed = id("gigconfirmed").checked ? 1 : 0;
    var comments = id("gigcomments").value;
    var attire = id("gigattire").value;
    var isInGoogleCalendar = id("isInGoogleCalendar").checked ? 1 : 0;
    
    var sendstr = "addgig&name="+name+"&date="+date+"&loadtime="+loadtime+"&starttime="+starttime+"&endtime="+endtime+"&location="+location+"&confirmed="+confirmed+"&comments="+comments+"&attire="+attire+"&isInGoogleCalendar="+isInGoogleCalendar;
    dopostajax(sendstr, addgigresponse);
    id("newgigdiv").innerHTML = "Processing...";
}

function addgigresponse(x) {
  id("newgigdiv").innerHTML = x;
}

</script>

</td></tr>
