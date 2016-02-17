<?php
/**
 *
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.View.Layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

$this->Html->css([
	'//maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css',
	'//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css', 
], null, ['inline' => false, 'block' => 'cssFirst']);

$this->Html->script([
	'//ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js',
	'//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js'
], ['inline' => false, 'block' => 'jsFirst']);

// TypeKit
$this->Html->script('https://use.typekit.net/dif7tyo.js', ['inline' => false]);
$this->Html->scriptStart(['inline' => false]); ?>
	try{Typekit.load({ async: true });}catch(e){}
<?php $this->Html->scriptEnd();

?>
<!DOCTYPE html>
<html>
<head>
	<?php echo $this->Html->charset(); ?>
	<title>
		<?php echo $title_for_layout; ?>
	</title>
	<?php
		echo $this->Html->meta('icon');

		echo $this->Html->css('style');

		echo $this->fetch('meta');
		//echo $this->fetch('css');
		//echo $this->fetch('script');
		echo $this->Asset->output(true, false, 'css');
	?>
</head>
<body>
	<div id="container">
		<div id="header">
			<div class="container-fluid">
				<?php echo $this->fetch('header'); ?>
			</div>
		</div>
		<div id="content">
			
			<?php 
			$open = '<li>';
			$close = '</li>';
			if ($crumbs = $this->Html->getCrumbs("$close$open")):  ?>
				<ul class="breadcrumb"><?php 
					echo $open . $this->Html->link('<i class="fa fa-home"></i>', '/', ['escape' => false]) . $close;
					echo $open . $crumbs . $close; 
				?></ul>
			<?php endif; ?>

			<div class="container-fluid">
				<?php echo $this->Session->flash(); ?>
				<?php echo $this->fetch('content'); ?>
			</div>
		</div>
		<div id="footer">
			<div class="container-fluid">
				<?php echo $this->fetch('footer'); ?>
			</div>
		</div>
	</div>
	<?php if ($this->Session->read('Auth.User.is_admin')): ?>
		<div class="container">
			<?php echo $this->element('sql_dump'); ?>
		</div>
	<?php endif; ?>
	<?php echo $this->Asset->output(true, false); ?>
</body>
</html>
