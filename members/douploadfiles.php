<html>
<head>
<link rel='stylesheet' type='text/css' href='css.css' />
<style type='text/css'>
    input {border:2px solid #ccc};
    select {border:2px solid #ccc};
</style>
</head>
<body>

<?php

$type = 0;
$error = "Must be a PDF or MIDI file. ";
$filename = "";
if ($_FILES["file"]["type"] == "application/pdf") {
  if ($_FILES["file"]["error"] > 0)
    {
    $error = "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
    if (file_exists("scores/" . $_FILES["file"]["name"]))
      {
      $error = $_FILES["file"]["name"] . " already exists. ";
      }
    else if (move_uploaded_file($_FILES["file"]["tmp_name"],
				"scores/" . $_FILES["file"]["name"])) {
      $type = 1;
      $filename = "scores/" . $_FILES["file"]["name"];
      }
    }
  }

if ($_FILES["file"]["type"] == "audio/midi") {
  if ($_FILES["file"]["error"] > 0)
    {
    $error = "Return Code: " . $_FILES["file"]["error"] . "<br />";
    }
  else
    {
    if (file_exists("midis/" . $_FILES["file"]["name"]))
      {
      $error = $_FILES["file"]["name"] . " already exists. ";
      }
    else if (move_uploaded_file($_FILES["file"]["tmp_name"],
				"midis/" . $_FILES["file"]["name"])) {
      $type = 2;
      $filename = "midis/" . $_FILES["file"]["name"];
      }
    }
  }

if ($type == 0) {
  echo "Error:<br />" . $error;
  echo "<br />&nbsp;<br />";
  echo "<input type='button' value='Try Again' onclick='window.location=\"uploadfiles.php\"'>";
}
else {
  $filename = "https://www.stanford.edu/group/calypso/cgi-bin/members/" . $filename;
  echo "<script type='text/javascript'>";
  if ($type == 1)
    echo "parent.document.getElementById('score').value = '" . $filename . "';";
  else if ($type == 2)
    echo "parent.document.getElementById('midi').value = '" . $filename . "';";
  echo "</script>";
}

if ($type == 1)
  echo "Successfully uploaded PDF file to <a href='" . $filename . "'>" . $filename ."</a>.";
else if ($type == 2)
  echo "Successfully uploaded MIDI file to <a href='" . $filename . "'>" . $filename ."</a>.";

if ($type != 0)
  echo "<br />&nbsp;<br /><input type='button' value='Upload Another File' onclick='window.location=\"uploadfiles.php\"'>";
	    


?>


</body>
</html>