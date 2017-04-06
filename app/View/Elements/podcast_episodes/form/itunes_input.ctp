<fieldset>
	<legend>iTunes</legend>
	<?php
	echo $this->Form->input('explicit', [
		'label' => 'Explicit Content',
		'class' => 'checkbox',
		'help' => 'Does this podcast have explicit content? (Needed for iTunes)',
	]);
	echo $this->Form->input('keywords', [
		'help' => 'To assist with finding the podcast. Separate values with a comma',
		'placeholder' => 'tag1, tag2, tag3',
	]);
	?>
</fieldset>
