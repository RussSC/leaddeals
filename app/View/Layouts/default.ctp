<?php

$this->Html->css([
	'style',
], null, ['inline' => false, 'block' => 'cssFirst']);

$socialMedia = [
	'Facebook' => [
		'icon' => '<i class="fa fa-facebook"></i>',
		'url' => 'https://www.facebook.com/Lead-Deals-Productions-111002299065/',
	],
	'Twitter' => [
		'icon' => '<i class="fa fa-twitter"></i>',
		'url' => 'https://twitter.com/leaddeals',
	]
];

echo $this->element('layout_data/crumbs');

$this->extend('html');
$this->start('header'); ?>
	<div class="layout-header">
		<div class="layout-header-logo">
			<?php echo $this->Html->image('layout/logo-banner.jpg', [
				'alt' => 'Lead Deals Productions',
				'url' => '/',
			]); ?>
		</div>
		<div class="layout-header-body">
			<?php echo $this->Bootstrap->linkList($navMenu, ['class' => 'layout-header-nav', 'urlActive' => true]); ?>

			<div class="layout-header-login">
				<?php if ($this->Session->check('Auth.User.id')): ?>
					<?php echo $this->Html->link(
						$this->Session->read('Auth.User.name'),
						['controller' => 'users', 'action' => 'view', $this->Session->read('Auth.User.id')],
						['class' => 'layout-header-login-user']
					); ?>

					<?php echo $this->Html->link(
						'Log out',
						['controller' => 'users', 'action' => 'logout']
					); ?>
				<?php else: ?>
					<?php echo $this->Html->link('Login',
						['controller' => 'users', 'action' => 'login']
					); ?>
				<?php endif; ?>
			</div>
		</div>
	</div>
<?php $this->end();

$this->start('footer'); ?>
	<div class="layout-footer">
		<?php echo $this->Bootstrap->linkList($navMenu, ['class' => 'layout-footer-nav', 'urlActive' => true]); ?>
		<h3>Lead Deals Productions</h3>
		<p>Copyright <?php echo date('Y'); ?></p>

		<div class="layout-footer-social">
			<?php foreach ($socialMedia as $title => $config): 
				echo $this->Html->link(
					$config['icon'], $config['url'], [
						'escape' => false,
					]); 
			endforeach; ?>
		</div>
	</div>
<?php $this->end();

echo $this->fetch('content');
