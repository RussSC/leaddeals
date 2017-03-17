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
		'itunes' => [
			'title' => 'iTunes',
			'icon' => 'apple',
		],
		'tumblr' => [
			'title' => 'Tumblr',
			'icon' => 'tumblr',
		]
	];

	public function inputTypeSelect($name, $options = []) {
		$valueOptions = [];
		foreach ($this->linkTypes as $key => $config) {
			$valueOptions[$key] = $config['title'];
		}
		$options['options'] = $valueOptions;
		return $this->Form->input($name, $options);
	}

	public function link($url, $type = '', $options = []) {
		if (strpos($url, '://') === false) {
			$url = 'http://' . $url;
		}
		$options['target'] = '_blank';
		if (!empty($this->linkTypes[$type])) {
			$config = $this->linkTypes[$type];
			$title = '<span class="share-link-title">' . $config['title'] . '</span>';
			if (!empty($config['icon'])) {
				$title = $this->Html->tag('span', 
					'<i class="fa fa-fw fa-' . $config['icon'] . '"></i>',
					['class' => 'btn-icon']
				) . $title;
			}
		} else {
			$title = $url;
		}
		$options['escape'] = false;
		$options = $this->addClass($options, 'share-link');
		return $this->Html->link($title, $url, $options);
	}
}