<?php 
// Set Variables
$default = [
	'contentHeading' => '',
	'contentTitle' => '',
	'pageTitle' => '',
	'contentPanel' => true,
	'extend' => 'default',
];
// Gets User Data
$centeredContent = !empty($centeredContent) ? array_merge($default, $centeredContent) : $default;
extract($centeredContent);

$this->extend($extend); 

$contentPanelClass = $contentPanel ? 'centered-content-panel panel panel-default' : '';

if (empty($contentClass)) {
	$contentClass = '';
}
$contentClass .= ' centered-content';
$this->set(compact('contentClass'));


if (!empty($pageTitle)) {
	if (!empty($contentTitle)) {
		$contentTitle .= ' : ';
	}
	$contentTitle .= $pageTitle;
	//$this->Crumbs->title($pageTitle);
}
if (!empty($contentTitle)) {
	$contentHeading .= $this->Html->tag('span', $contentTitle, ['class' => 'panel-title']);
}

$this->Html->css('CenteredContent.style', null, ['inline' => false]);
?>

<div class="<?php echo trim($contentClass); ?>">
	<?php echo $this->fetch('beforeContent'); ?>
	<div class="<?php echo $contentPanelClass; ?>">
		<?php if (!empty($contentHeading)): ?>
			<div class="panel-heading">
				<?php echo $contentHeading; ?>
			</div>
		<?php endif; ?>

		<div class="panel-body">
			<?php echo $this->fetch('content'); ?>
		</div>

		<?php if ($footer = $this->fetch('contentFooter')): ?>
			<div class="panel-footer">
				<?php echo $footer; ?>
			</div>
		<?php endif; ?>
	</div>
	<?php echo $this->fetch('afterContent'); ?>
</div>