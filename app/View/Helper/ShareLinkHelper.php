<?php
class ShareLinkHelper extends AppHelper {
	public $helpers = ['Html', 'Form'];

	protected $linkTypes = [
		'' => [
			'title' => 'Link',
			'icon' => 'link',
		],
		'email' => [
			'title' => 'Email',
			'icon' => 'envelope-o',
		],
		'facebook' => [
			'title' => 'Facebook',
			'icon' => 'facebook',
		],
		'twitter' => [
			'title' => 'Twitter',
			'icon' => 'twitter',
		],
		'google' => [
			'title' => 'Google',
			'icon' => 'google',
		],
	];

	public function inputTypeSelect($name, $options = []) {
		$valueOptions = [];
		foreach ($this->linkTypes as $key => $config) {
			$valueOptions[$key] = $config['title'];
		}
		$options['options'] = $valueOptions;
		return $this->Form->input($name, $options);
	}

	public function link($url, $type = '') {
		if (!empty($this->linkTypes[$type])) {
			$config = $this->linkTypes[$type];
			$title = '<span class="share-link-title">' . $config['title'] . '</span>';
			if (!empty($config['icon'])) {
				$title = '<i class="fa fa-' . $config['icon'] . '"></i>' . $title;
			}
		} else {
			$title = $url;
		}
		return $this->Html->link($title, $url, [
			'escape' => false,
			'class' => 'share-link',
		]);
	}
}