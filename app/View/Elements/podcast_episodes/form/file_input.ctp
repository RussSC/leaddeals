<fieldset>
	<legend>File Information</legend>
	<?php
	// Add these fields
	echo $this->Form->input('download_url', [
		'label' => 'Direct Download URL',
		'help' => 'Paste the URL where the mp3 is stored',
		'placeholder' => 'http://',
	]);

	echo $this->Form->input('libsyn_id', [
		'label' => 'Libsyn ID',
		'help' => 'If you want to use the Libsyn player, add the 7-digit Libsyn episode ID',
		'type' => 'number',
		'placeholder' => '0000000'
	]);

	$inputDefaults = $this->Form->inputDefaults();
	$this->Form->inputDefaults([
		'div' => 'form-group col col-xs-4',
		'default' => '00',
		'type' => 'text',
		'placeholder' => '00',
	], true);
	?>
	<label>File length</label>
	<div class="row">
		<?php 
		echo $this->Form->input('duration_hh', ['label' => 'HH']);
		echo $this->Form->input('duration_mm', ['label' => 'MM']);
		echo $this->Form->input('duration_ss', ['label' => 'SS']);
		?>
	</div>
	<div class="help-block">How long is your mp3?</div>
	
	<?php
	$this->Form->inputDefaults($inputDefaults);
	/*
	echo $this->Form->input('filesize', [
		'type' => 'number',
		'help' => 'How large the podcast is (in bytes)',
	]);
	*/
	?>
</fieldset>
