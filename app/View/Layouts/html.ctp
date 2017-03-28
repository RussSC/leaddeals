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
	'//ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.min.css', 
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
	<meta name="google-site-verification" content="ZEhySQpPUlniu-VlpMjCxcYzVCoDAoVLfIy-YydnFFk" />
	<?php // Facebook sharing ?>
	<?php echo $this->Html->meta(['property' => 'og:title', 'content' => trim($title_for_layout)]); ?>
	<?php echo $this->Html->meta(['property' => 'og:type', 'content' => 'website']); ?>
	<?php echo $this->Html->meta(['property' => 'og:url', 'content' => Router::url($this->request->here(false), true)]);  ?>

	<?php if (!empty($description_for_layout)):
			if (!empty($this->DisplayText)) {
				$description_for_layout = $this->DisplayText->text($description_for_layout);
			}
			$description_for_layout = trim(str_replace("\n", '', strip_tags($description_for_layout)));
			echo $this->Html->meta('description', $description_for_layout);
			echo $this->Html->meta(['property' => 'og:description', 'content' => $description_for_layout]);
		endif;
		if (!empty($image_for_layout)) {
			echo $this->Html->tag('link', '', array(
				'rel' => 'image_src',
				'href' => $image_for_layout
			));
			echo $this->Html->meta(['property' => 'og:image', 'content' => $image_for_layout]);
			if (!empty($image_for_layout_properties)) {
				foreach ($image_for_layout_properties as $key => $val) {
					echo $this->Html->meta(['property' => 'og:image:' . $key, 'content' => $val]);
				}
			}
		}
	?>
	<?php
		echo $this->Html->meta('icon');
		echo $this->fetch('meta');
		//echo $this->fetch('css');
		//echo $this->fetch('script');
		echo $this->Asset->output(true, false, 'css');
	?>
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

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
		<header id="header">
			<div class="container-fluid">
				<?php echo $this->fetch('header'); ?>
			</div>
		</header>
		<main id="content">
			
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
				<?php echo $this->Flash->render(); ?>
				<?php echo $this->fetch('content'); ?>
			</div>
		</main>
		<footer id="footer">
			<div class="container-fluid">
				<?php echo $this->fetch('footer'); ?>
			</div>
		</footer>
	</div>
	<?php if ($this->Session->read('Auth.User.is_admin')): ?>
		<section id="admin-footer" class="container">
			<?php echo $this->element('sql_dump'); ?>
		</section>
	<?php endif; ?>
	<?php echo $this->Asset->output(true, false); ?>

	<script>
	  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

	  ga('create', 'UA-75192726-1', 'auto');
	  ga('send', 'pageview');

	</script>
</body>
</html>
