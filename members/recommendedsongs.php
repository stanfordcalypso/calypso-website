<?php
include "connect.php";
include_once(dirname(__FILE__) . "/../dbcon.php");
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
<script type="text/javascript" src="songTable.js"></script>
<script type="text/javascript">
  function populateGigInfo(sql_result) {
    let gigname = sql_result.substring(0, sql_result.indexOf(smalldivider));
    document.getElementById('giginfo').innerHTML = 'Recommended Songs for ' + gigname;
  }
  doajax('singlegig&gigid=' + <?php echo $_GET[gigid] ?>, populateGigInfo);
</script>
<!-- <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script> -->
</head>
<body><div align='center'><table id='wrapper' cellspacing='0' cellpadding='0'>

  <tr>
    <td id='left' colspan='2'>
      <div id='logo'></div>
      <table id='nav' cellspacing='0' cellpadding='0'><tr>
	<td><a href='http://www.stanford.edu/group/calypso/cgi-bin/'>Public Site</a></td>
</tr></table>
    </td> 
  </tr><tr>
    <td id='content'><table cellspacing='0' cellpadding='0' style='height:400px'>

<tr><td colspan='2' style='height:10px'>
</td></tr>

<p id='giginfo'></p>
<?php
//echo "<center>Site is temporarily down because we are out of room on AFS and I don't want the database getting messed up.</center>";
//exit();
include "songsforgig.php";
mysql_close($con);

?>


</table>
</td>

  </tr>
  <tr id='foot'><td colspan='2'>Copyright &copy;2006-<?=date('Y')?> Cardinal Calypso &middot; <a href='http://assu.stanford.edu' target='_blank'>ASSU</a> &middot; <a href='http://www.facebook.com/group.php?gid=2200276540' target='_blank'>FB</a></td></tr>
</table></div></body>
</html>
