<?php
	function get_midi_filenames() {
		$result = array();
		if ($handle = opendir("midi")) {
			while (($entry = readdir($handle)) !== false) {
				if ($entry == "." or $entry == "..")
					continue;
				array_push($result, $entry);
			}
			closedir($handle);
		}
		return $result;
	}
?>
