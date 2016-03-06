<?php
App::uses('RssHelper', 'View/Helper');
class AppRssHelper extends RssHelper {

	protected $_namespaces = [];

	public function registerNamespaces($namespaces) {
		foreach ($namespaces as $k => $v) {
			$this->registerNamespace($k, $v);
		}
	}

	public function registerNamespace($ns, $url) {
		$this->_namespaces[$ns] = $url;
	}

/**
 * Returns an RSS document wrapped in `<rss />` tags
 *
 * @param array $attrib `<rss />` tag attributes
 * @param string $content
 * @return string An RSS document
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/rss.html#RssHelper::document
 */
	public function document($attrib = array(), $content = null) {
		if (!empty($attrib['namespaces'])) {
			$this->registerNamespaces($attrib['namespaces']);
			unset($attrib['namespaces']);
		}
		if (!empty($this->_namespaces)) {
			foreach ($this->_namespaces as $name => $url) {
				$attrib['xmlns:' . $name] = $url;
			}
		}
		return parent::document($attrib, $content);
	}

/**
 * Generates an XML element
 *
 * @param string $name The name of the XML element
 * @param array $attrib The attributes of the XML element
 * @param string|array $content XML element content
 * @param boolean $endTag Whether the end tag of the element should be printed
 * @return string XML
 * @link http://book.cakephp.org/2.0/en/core-libraries/helpers/rss.html#RssHelper::elem
 */
	public function elem($name, $attrib = array(), $content = null, $endTag = true) {
		if (!empty($content['attrib'])) {
			$attrib = $content['attrib'];
			unset($content['attrib']);
		}
		$nsSplit = '_____________';
		if (!empty($attrib['namespace']) && is_string($attrib['namespace']) && !empty($this->_namespaces[$attrib['namespace']])) {
			$name = $attrib['namespace'] . $nsSplit . $name;
			//debug($name);
			unset($attrib['namespace']);
		}
		$xml = parent::elem($name, $attrib, $content, $endTag);
		return str_replace($nsSplit, ':', $xml) . "\n";
	}
}