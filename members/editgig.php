<tr><td colspan=2>
<h1>Edit Gig</h1>
<br />
<?php
$gigname = "";
$gigdate = "";
$gigloadtime = "";
$gigstarttime = "";
$gigendtime = "";
$giglocation = "";
$gigcomments = "";
$gigconfirmed = "";
$gigattire = "";

if (isset($_GET['gigid'])) {
  $newsong = 0;
  $gigid = $_GET['gigid'];

  $result = mysql_query("SELECT name, date, loadtime, starttime, endtime, location, comments, confirmed, attire FROM gigs WHERE gigid = '$gigid'");
  if ($row = mysql_fetch_array($result)) {
    $gigname = $row['name'];
    $gigdate = $row['date'];
    $gigloadtime = $row['loadtime'];
    $gigstarttime = $row['starttime'];
    $gigendtime = $row['endtime'];
    $giglocation = $row['location'];
    $gigcomments = $row['comments'];
    $gigconfirmed = $row['confirmed'];
    $gigposted;
    $gigattire = $row['attire'];
  }
}

?>
<div id="editgigdiv">
<table style='width:600px'>
<tr><td><div style='width:100px'>Name:</div></td><td><input size='20' id='gigname' value='<?php echo $gigname; ?>'></td>
<td style='padding-left:20px'><div style='width:100px'>Date:</div></td><td><div id="dateselect" style='width:300px'></span></td></tr>
<tr><td><div style='width:100px'>Load time:</div></td><td><div id="loadtime" style='width:200px'></div></td>
<td style='padding-left:20px'><div style='width:100px'>Start time:</div></td><td><div id="starttime"></div></td></tr>
<tr><td><div style='width:100px'>End time:</div></td><td><div id="endtime"></div></td>
<td style='padding-left:20px'><div style='width:100px'>Location:</div></td><td><input size='20' id='giglocation' value='<?php echo $giglocation; ?>'></td></tr>
	
	<tr>
		<td colspan=2>Attire:<br /><textarea id='gigattire' row='3' cols='25'><?php echo $gigattire; ?></textarea></td>
	 	<td style='padding-left:20px'><div style='width:100px'>Confirmed:</div></td><td><input type='checkbox' id='gigconfirmed'<?php if ($gigconfirmed == 1){echo " checked";} ?>></td>
	</tr>
  	<tr>
  		<td colspan=2>Comments:<br /><textarea id='gigcomments' row='3' cols='25'><?php echo $gigcomments; ?></textarea></td>
  		<!-- <td style='padding-left:20px'><div style='width:100px'>Post to Calendar?:</div></td><td><input type='checkbox' id='gigposted'<?php if ($gigposted == 1){echo " checked";} ?>></td> -->
  </tr></table>

<br />

<input type="button" value="Submit Changes" onclick="editgig()">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" value="Delete Gig" onclick="deletegig()">
</div>

<script type="text/javascript">
/*
var clientId = '1072266037494'
var apiKey = 'AIzaSyCGzO_yilbkluevZgy4q_4nOdMxkj7OYkk';
var scopes = 'https://www.googleapis.com/auth/calendar';
*/

id("dateselect").innerHTML = dateselectedit("gigdate", "<?php echo $gigdate; ?>");
id("loadtime").innerHTML = timeselectedit("loadtime", "<?php echo $gigloadtime; ?>");
id("starttime").innerHTML = timeselectedit("starttime", "<?php echo $gigstarttime; ?>");
id("endtime").innerHTML = timeselectedit("endtime", "<?php echo $gigendtime; ?>");

function editgig() {
    var name = id("gigname").value;
    var date = getdate("gigdate");
    var loadtime = gettime("loadtime");
    var starttime = gettime("starttime");
    var endtime = gettime("endtime");
    var location = id("giglocation").value;
    var confirmed = id("gigconfirmed").checked ? 1 : 0;
    var comments = id("gigcomments").value;
    var attire = id("gigattire").value;
    var sendstr = "editgig&gigid=<?php echo $gigid; ?>&name="+name+"&date="+date+"&loadtime="+loadtime+"&starttime="+starttime+"&endtime="+endtime+"&location="+location+"&confirmed="+confirmed+"&comments="+comments+"&attire="+attire;
    
    dopostajax(sendstr, editgigresponse);
    id("editgigdiv").innerHTML = "Processing...";
}

function editgigresponse(x) {
  id("editgigdiv").innerHTML = x;
}

function deletegig() {
if (confirm("Are you sure? This will delete the gig permanently, along with all responses associated with it.")) {
   var sendstr = 'deletegig&gigid=<?php echo $gigid; ?>';
   dopostajax(sendstr, editgigresponse);
   id("editgigdiv").innerHTML = "Processing...";
}
}

/*
function checkAuth() {
    gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: true}, handleAuthResult);
}


function handleAuthResult(authResult) {
        if (authResult && !authResult.error) {
          makeApiCall();
        } else {
        	gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false}, handleAuthResult);
        	return false;
        }
}

// Load the API and make an API call.  Display the results on the screen.
      function makeApiCall() {
        gapi.client.load('calendar', 'v3', function() {
          var resource = {
  			"summary": "FACES",
  			"location": "Memorial Auditorium",
  			"start": {
    			"dateTime": "2013-09-18T10:00:00.000-07:00"
  			},
  			"end": {
    			"dateTime": "2013-09-18T10:25:00.000-07:00"
  			}
		};
		var request = gapi.client.calendar.events.insert({
  			'calendarId': 'tuleai9qf617ins2h47jfeiqac@group.calendar.google.com',
  			'resource': resource
		});
		request.execute(function(resp) {
  			console.log(resp);
		});
        });
      }
      */
</script>

</td></tr>