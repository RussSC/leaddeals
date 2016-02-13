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
		echo $this->fetch('css');
		echo $this->fetch('script');
	?>
</head>
<body>
	<div id="container" class="container">
		<div id="header">
			<h1><?php echo $this->Html->link('Lead Deals Productions', '/'); ?></h1>
			<ul>
				<li><?php echo $this->Html->link('News', ['controller' => 'articles', 'action' => 'index']); ?></li>
				<li><?php echo $this->Html->link('Projects', ['controller' => 'projects', 'action' => 'index']); ?></li>
				<li><?php echo $this->Html->link('Contact', ['controller' => 'pages', 'action' => 'display', 'contact']); ?></li>
			</ul>
		</div>
		<div id="content" class="container">
			<?php echo $this->Session->flash(); ?>
			<?php echo $this->fetch('content'); ?>
		</div>
		<div id="footer" class="container">
			<h3>Lead Deals Productions</h3>
			<p>Copyright <?php echo date('Y'); ?></p>
		</div>
	</div>
	<div class="container">
		<?php echo $this->element('sql_dump'); ?>
	</div>
</body>
</html>
