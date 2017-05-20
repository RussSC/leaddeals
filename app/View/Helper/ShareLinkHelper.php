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
			'share' => 'https://www.facebook.com/sharer/sharer.php?u=$url',
		],
		'twitter' => [
			'title' => 'Twitter',
			'icon' => 'twitter',
			'match' => ['/^[@]{0,1}([A-Za-z0-9]+)$/', 'https://twitter.com/$1'],
			'share' => 'https://twitter.com/share?text=$title&url=$url',
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
		],
		'instagram' => [
			'title' => 'Instagram',
			'icon' => 'instagram',
			'match' => ['/^([A-Za-z0-9]+)$/', 'https://instagram.com/$1'],
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

	public function share($url, $title = "") {
		if(is_array($url)) {
			$url = Router::url($url, true);
		}

		$out = '';
		foreach ($this->linkTypes as $type => $linkType) {
			if (!empty($linkType['share'])) {
				$shareUrl = str_replace(['$url', '$title'], [$url, urlencode($title)], $linkType['share']);
				$out .= $this->link($shareUrl, $type, ['class' => 'btn btn-default']) . "\r\n";
			}
		}
		$title = $this->Html->tag('span', 'Share:', ['class' => 'share-link-list-title']) . "\r\n";
		return $this->Html->div('share-link-list', $title . $out);
	}

	public function link($url, $type = '', $options = []) {
		$isLink = strpos($url, '://') !== false;
		if (!$isLink) {
			if (strpos($url, '@') !== false) {
				if (strpos($url, 'mailto:') === false) {
					$url = 'mailto:' . $url;
				}
			} else if (!empty($this->linkTypes[$type]['match'])) {
				list($match, $replace) = $this->linkTypes[$type]['match'];
				$url = preg_replace($match, $replace, $url);
			} else {
				$url = 'http://' . $url;
			}
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
