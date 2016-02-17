<?php
$this->extend('CenteredContent.default');

$default = [
	'hasSubmit' => true,
	'submitText' => 'Submit',
	'formAdd' => false,

	'backUrl' => false,
	'backText' => 'Back',
	'backIcon' => '<i class="fa fa-arrow-left"></i>',

	'submitUrl' => false,
	'submitText' => 'Next',
	'submitIcon' => '<i class="fa fa-check"></i>',

	'formOptions' => [],
	'submittedOverlay' => true,
];

if (!empty($this->Form->defaultModel)) {
	$model = $this->Form->defaultModel;
	$modelHuman = Inflector::humanize(Inflector::underscore($model));

	$default['formAdd'] = !$this->Form->value("$model.id");
	$default['submitText'] = $default['formAdd'] ? 'Add' : 'Update';
	$default['submitText'] .= " $modelHuman";

	$default['pageTitle'] = $default['submitText'];
}

if (!empty($formAdd) || !empty($default['formAdd'])) {
	$default['submitIcon'] = '<i class="fa fa-plus"></i>';
}

// Gets User Data
$centeredContent = !empty($centeredContent) ? array_merge($default, $centeredContent) : $default;
extract($centeredContent);

// Includes the Layout's submitted_overlay
if ($submittedOverlay) {
	$this->Html->css('Layout.form_submitted_overlay', null, ['inline' => false]);
	$this->Html->script('Layout.form_submitted_overlay', ['inline' => false]);
	$formOptions = $this->Html->addClass($formOptions, 'submitted-overlay');
}

if (empty($contentClass)) {
	$contentClass = '';
}
$this->set('contentClass', $contentClass . ' centered-form-content');


// Add Icons to Buttons
if (!empty($backIcon) && !empty($backText)) {
	$backText = "$backIcon $backText";
}
if (!empty($submitIcon) && !empty($submitText)) {
	$submitText = "$submitIcon $submitText";
}

$backClass = 'pull-left btn btn-default btn-lg';
$submitClass = 'btn btn-primary btn-lg';

// Create buttons
if (!empty($backUrl)) {
	$backButton = $this->Html->link($backText, $backUrl, [
		'class' => $backClass,
		'escape' => false, 
	]);
}

if (!empty($submitUrl)) {
	$submitButton = $this->Html->link($submitText, $submitUrl, ['escape' => false, 'class' => $submitClass]);
} else if (!empty($hasSubmit)) {
	$submitButton = $this->Form->button($submitText, ['type' => 'submit', 'class' => $submitClass]);
}

// Output buttons
$footer = '';
$footerClass = !empty($submitButton) && !empty($backButton) ? 'text-right' : 'text-center';
if (!empty($submitButton)):
	$footer .= $submitButton;
endif;
if (!empty($backButton)):
	$footer .= $backButton;
endif;
if (!empty($footer)) {
	$this->append('contentFooter', $this->Html->div($footerClass, $footer));
}

$this->append('beforeContent', $this->Form->create(null, $formOptions));
$this->append('afterContent', $this->Form->end());

echo $this->fetch('content');