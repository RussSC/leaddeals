<?php
/**
 * This is Sessions Schema file
 *
 * Use it to configure database for Sessions
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
 * @package       app.Config.Schema
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

/*
 *
 * Using the Schema command line utility
 * cake schema run create Sessions
 *
 */
class SessionsSchema extends CakeSchema {

	public $name = 'Sessions';

	public function before($event = []) {
		return true;
	}

	public function after($event = []) {
	}

	public $cake_sessions = [
		'id' => ['type' => 'string', 'null' => false, 'key' => 'primary'],
		'data' => ['type' => 'text', 'null' => true, 'default' => null],
		'expires' => ['type' => 'integer', 'null' => true, 'default' => null],
		'indexes' => ['PRIMARY' => ['column' => 'id', 'unique' => 1]]
	];

}
