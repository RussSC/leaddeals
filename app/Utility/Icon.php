<?php 
/** 
 * Library to handle outputting Icon font sets
 *
 * Currently Using Font-Awesome 4.5
 * https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css
 *
 **/
App::uses('Inflector', 'Utility');
class Icon {

	// Alias array of terms that don't match to Icon ic class names
	protected static $_aliases = [
		'reset' => 				'refresh',
		'rotate-left' => 		'reply',
		'rotate-right' => 		'forward',
		'activate' => 			'check',
		'deactivate' => 		'ban',
		'delete' => 			'trash',
	];

	public static function __callStatic($name, $args) {
		$extraClass = !empty($args[0]) ? $args[0] : null;
		return self::out($name, $extraClass);
	}

	public static function out($type, $extraClass = null) {
		$type = str_replace('_', '-', Inflector::underscore($type));
		if (!empty(self::$_aliases[$type])) {
			$type = self::$_aliases[$type];
		}
		$class = 'fa fa-' . $type;
		if (!empty($extraClass)) {
			$class .= ' ' . $extraClass;
		}
		return '<i class="' . $class . '"></i>';
	}	
}