<?php
/**
 * This file is loaded automatically by the app/webroot/index.php file after core.php
 *
 * This file should load/create any application wide configuration settings, such as
 * Caching, Logging, loading additional configuration files.
 *
 * You should also use this file to include any files that provide global functions/constants
 * that your application uses.
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
 * @package       app.Config
 * @since         CakePHP(tm) v 0.10.8.2117
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */

// Setup a 'default' cache configuration for use in the application.
Cache::config('default', ['engine' => 'File']);

/**
 * The settings below can be used to set additional paths to models, views and controllers.
 *
 * App::build([
 *     'Model'                     => ['/path/to/models/', '/next/path/to/models/'],
 *     'Model/Behavior'            => ['/path/to/behaviors/', '/next/path/to/behaviors/'],
 *     'Model/Datasource'          => ['/path/to/datasources/', '/next/path/to/datasources/'],
 *     'Model/Datasource/Database' => ['/path/to/databases/', '/next/path/to/database/'],
 *     'Model/Datasource/Session'  => ['/path/to/sessions/', '/next/path/to/sessions/'],
 *     'Controller'                => ['/path/to/controllers/', '/next/path/to/controllers/'],
 *     'Controller/Component'      => ['/path/to/components/', '/next/path/to/components/'],
 *     'Controller/Component/Auth' => ['/path/to/auths/', '/next/path/to/auths/'],
 *     'Controller/Component/Acl'  => ['/path/to/acls/', '/next/path/to/acls/'],
 *     'View'                      => ['/path/to/views/', '/next/path/to/views/'],
 *     'View/Helper'               => ['/path/to/helpers/', '/next/path/to/helpers/'],
 *     'Console'                   => ['/path/to/consoles/', '/next/path/to/consoles/'],
 *     'Console/Command'           => ['/path/to/commands/', '/next/path/to/commands/'],
 *     'Console/Command/Task'      => ['/path/to/tasks/', '/next/path/to/tasks/'],
 *     'Lib'                       => ['/path/to/libs/', '/next/path/to/libs/'],
 *     'Locale'                    => ['/path/to/locales/', '/next/path/to/locales/'],
 *     'Vendor'                    => ['/path/to/vendors/', '/next/path/to/vendors/'],
 *     'Plugin'                    => ['/path/to/plugins/', '/next/path/to/plugins/'],
 * ]);
 *
 */

/**
 * Custom Inflector rules can be set to correctly pluralize or singularize table, model, controller names or whatever other
 * string is passed to the inflection functions
 *
 * Inflector::rules('singular', ['rules' => [], 'irregular' => [], 'uninflected' => []]);
 * Inflector::rules('plural', ['rules' => [], 'irregular' => [], 'uninflected' => []]);
 *
 */

/**
 * Plugins need to be loaded manually, you can either load them one by one or all of them in a single call
 * Uncomment one of the lines below, as you need. Make sure you read the documentation on CakePlugin to use more
 * advanced ways of loading plugins
 *
 * CakePlugin::loadAll(); // Loads all plugins at once
 * CakePlugin::load('DebugKit'); //Loads a single plugin named DebugKit
 *
 */
 CakePlugin::loadAll();

/**
 * You can attach event listeners to the request lifecycle as Dispatcher Filter. By default CakePHP bundles two filters:
 *
 * - AssetDispatcher filter will serve your asset files (css, images, js, etc) from your themes and plugins
 * - CacheDispatcher filter will read the Cache.check configure variable and try to serve cached content generated from controllers
 *
 * Feel free to remove or add filters as you see fit for your application. A few examples:
 *
 * Configure::write('Dispatcher.filters', [
 *		'MyCacheFilter', //  will use MyCacheFilter class from the Routing/Filter package in your app.
 *		'MyPlugin.MyFilter', // will use MyFilter class from the Routing/Filter package in MyPlugin plugin.
 * 		['callable' => $aFunction, 'on' => 'before', 'priority' => 9], // A valid PHP callback type to be called on beforeDispatch
 *		['callable' => $anotherMethod, 'on' => 'after'], // A valid PHP callback type to be called on afterDispatch
 *
 * ]);
 */
Configure::write('Dispatcher.filters', [
	'AssetDispatcher',
	'CacheDispatcher'
]);

App::uses('Icon', 'Utility');

/**
 * Configures default file logging options
 */
App::uses('CakeLog', 'Log');
CakeLog::config('debug', [
	'engine' => 'File',
	'types' => ['notice', 'info', 'debug'],
	'file' => 'debug',
]);
CakeLog::config('error', [
	'engine' => 'File',
	'types' => ['warning', 'error', 'critical', 'alert', 'emergency'],
	'file' => 'error',
]);
