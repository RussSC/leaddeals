<?php
echo $this->Form->input('published', [
	'div' => 'form-group input-date',
	'label' => 'Publish Date',
	'help' => 'Post in the future to delay posting',
]);