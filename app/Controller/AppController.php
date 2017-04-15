<?php
/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
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
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

App::uses('Controller', 'Controller');
App::uses('Inflector', 'Utility');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {
	public $components = [
		'Session',
		'Cookie',
		'Auth' => [
			'loginAction' => [
				'controller' => 'users', 'action' => 'login',
				'staff' => false, 'admin' => false, 'ajax' => false, 'json' => false, 'plugin' => false, 
			],
			'ajaxLogin' => [
				'controller' => 'users', 'action' => 'login',
				'staff' => false, 'admin' => false, 'ajax' => false, 'json' => false, 'plugin' => false
			],
			'logoutRedirect' => [
				'controller' => 'users', 'action' => 'logout',
				'staff' => false, 'admin' => false, 'ajax' => false, 'json' => false, 'plugin' => false
			],
			'loginRedirect' => [
				'controller' => 'users', 'action' => 'you', 
				'staff' => false, 'admin' => false, 'ajax' => false, 'json' => false, 'plugin' => false
			],
			'authError' => 'Sorry, you do not have access to this page',
			'authorize' => ['Controller'],
			'authenticate' => [
				'Form' => ['userModel' => 'User', 'fields' => ['username' => 'email']],
			]
		],
		'Flash',
		'LayoutData',
		'RememberMe',
		'RequestHandler',
		'FormData.Crud',
	];

	public $helpers = [
		'Html' => [
			'className' => 'BoostCake.BoostCakeHtml',
		],
		'Form' => [
			'className' => 'AppForm',
		],
		'Paginator' => [
			'className' => 'BoostCake.BoostCakePaginator',
		],
		'Flash',
		'Layout.Bootstrap',
		'CakeAssets.Asset',
		'Podcast',
	];

	public function beforeFilter($options = []) {
		parent::beforeFilter($options);

		$params = $this->request->params;

		$action = $params['action'];
		$prefix = null;

		// Sets Layout based on Prefix
		if (!empty($params['prefix'])) {
			$prefix = $params['prefix'];
			$this->layout = $prefix;
			$action = substr($action, strlen($prefix) + 1);
			$this->layout .= '/' . $action;

			foreach (["$prefix/$action", $prefix] as $layout) {
				if ($this->LayoutData->checkLayout($layout)) {
					$this->layout = $layout;
					break;
				}
			}
		}

		// Allows all non-prefixed pages except forms
		if (empty($prefix)) {
			$this->Auth->allow();
			$this->Auth->deny(['add', 'edit', 'delete']);
		}
	}

	public function beforeRender($options = []) {
		$this->setNavMenu();
		return parent::beforeRender($options);
	}

	public function isAuthorized($user) {
		$allow = false;
		if (empty($this->params['prefix'])) {
			$allow = true;
		} else {
			if ($this->params['prefix'] == 'admin' && $this->Auth->user('is_admin')) {
				$allow = true;
			}
		}
		return $allow;
	}

	protected function fetchId($id = null, $Model = null) {
		if (empty($Model)) {
			$Model = $this->{$this->modelClass};
		}
		$named = [];
		if (!empty($this->request->named)) {
			$named = $this->request->named;
		}
		
		if (empty($id)) {
			if (!empty($named['id'])) {
				$id = $named['id'];
			} else if (!empty($named['slug'])) {
				$slug = $named['slug'];
			}
		}
		if (!is_numeric($id) && empty($slug)) {
			$slug = $id;
			$id = null;
		}
		if (empty($id) && !empty($slug) && $Model->hasMethod('findIdFromSlug')) {
			$id = $Model->findIdFromSlug($slug);
		}
		return $id;
	}

	private function setNavMenu() {
		$menu = [
			//['News', ['controller' => 'articles', 'action' => 'index']],
			//['Projects', ['controller' => 'projects', 'action' => 'index']],
			['Podcasts', ['controller' => 'podcasts', 'action' => 'index']],
			['Contact', ['controller' => 'pages', 'action' => 'display', 'contact']],
		];
		$this->set('navMenu', $menu);
	}
}
