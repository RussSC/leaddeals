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
	'//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js',
	'script',
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
		echo $this->fetch('meta');
		//echo $this->fetch('css');
		//echo $this->fetch('script');
		echo $this->Asset->output(true, false, 'css');
	?>

	<link rel="apple-touch-icon" sizes="57x57" href="/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">	

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
