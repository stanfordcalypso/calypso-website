<?php
	include_once ("funcs.php");
?>

<select id="midiselect">
	<?php
		$midi_filenames = get_midi_filenames();
		foreach ($midi_filenames as $name) {
	?>
			<option value="<?= $name ?>"><?= $name ?></option>
	<?php
		}
	?>
</select>
