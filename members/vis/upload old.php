<?php
	include_once ("funcs.php");

	$file = $_FILES["midifile"];
	if ($file["error"] > 0)
		echo "Error: " . $file["error"] . ".";
	else if ($file["type"] != "audio/mid" and $file["type"] != "audio/midi")
		echo "Error: file must be MIDI (type is " . $file["type"] . ").";
	else if (in_array ($file["name"], get_midi_filenames()))
		echo "Error: a MIDI file with that name has already been uploaded.";
	else if (!copy($file["tmp_name"], "midi/" . $file["name"]))
		echo "Error copying file from temporary location.";
	else
		echo "Successfully uploaded " . $file["name"] . ".";
?>
