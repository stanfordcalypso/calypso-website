<html>
<head>
<link rel='stylesheet' type='text/css' href='css.css' />
<style type='text/css'>
    input {border:2px solid #ccc};
    select {border:2px solid #ccc};
</style>
</head>
<body>

<form action="douploadfiles.php" method="post" enctype="multipart/form-data">
PDF or MIDI:<br />
<input name="file" type="file">
	    <br />&nbsp;<br />
<input type="submit" name="submit" value="Upload">
</form>

</body>
</html>