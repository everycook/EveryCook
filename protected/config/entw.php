<?php
/*
This is the EveryCook Recipe Database. It is a web application for creating (and storing) machine (and human) readable recipes.
These recipes are linked to foods and suppliers to allow meal planning and shopping list creation. It also guides the user step-by-step through the recipe with the CookAssistant
EveryCook is an open source platform for collecting all data about food and make it available to all kinds of cooking devices.

This program is copyright (C) by EveryCook. Written by Samuel Werder, Matthias Flierl and Alexis Wiasmitinow.

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

See GPLv3.htm in the main folder for details.
*/

// uncomment the following to define a path alias
// Yii::setPathOfAlias('local','path/to/local-folder');

// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'EveryCook',

	// preloading 'log' component
	'preload'=>array('log'),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.extensions.fancybox.EFancyBox',
		'application.extensions.select2.ESelect2',
		'packages.solr.*',
		'application.models_solr.*',
	),
	"aliases" => array(
		"packages" => dirname(__DIR__)."/packages/",
	),

	'modules'=>array(
		'admin',
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'gii',
			// If removed, Gii defaults to localhost only. Edit carefully to taste.
			'ipFilters'=>array('127.0.0.1'
	                           ,'::1'),
		),
	),

	// application components
	'components'=>array(
		'user'=>array(
			'class'=>'WebUser',
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'autoRenewCookie' => true,
			'authTimeout' => 31557600,
		),
		'session' => array(
			'class'=>'CHttpSession',
			'timeout'=>3600, // 1h
			//'autoStart'=>true,
		),
		// uncomment the following to enable URLs in path-format
		'urlManager'=>array(
			'urlFormat'=>'path',
			'showScriptName'=>false,
			//'caseSensitive'=>false,
			'rules'=>array(
				// call gii by /path/to/index.php/gii
				'gii'=>'gii',
				'gii/<controller:\w+>'=>'gii/<controller>',
				'gii/<controller:\w+>/<action:\w+>'=>'gii/<controller>/<action>',
				'admin'=>'admin',
				'<controller:\w+>'=>'<controller>/index',
				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
				'<controller:\w+>/<action:\w+>/<id:\w+><ext:\.\w+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>/<id:backup>'=>'<controller>/<action>',
				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
			),
		),
		/*
		'db'=>array(
			'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		
		'db'=>array(
			'class'=>'CDbConnection',
			'driverMap'=>array('mysql'=>'MysqlGeomSchema'),
			'connectionString' => 'mysql:host=localhost;dbname=ec',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'schemaCachingDuration' => 60*60*6, // 6stunden
			//'enableParamLogging'=>true,
			//'enableProfiling'=>true,
		),

		// for tables profiles, meals, meals_to_cou, shoplists
		'dbp'=>array(
			'class'=>'CDbConnection',
			'driverMap'=>array('mysql'=>'MysqlGeomSchema'),
			'connectionString' => 'mysql:host=localhost;dbname=ec_priv',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'schemaCachingDuration' => 60*60*6, // 6stunden
			//'enableParamLogging'=>true,
			//'enableProfiling'=>true,
		),
		
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'class'=>'ErrorHandler',
			'errorAction'=>'site/error',
		),
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning, trace',
				),
				/*//uncomment to see executed querys in logfile
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'trace',
					'categories' => 'system.db.CDbCommand'
				),
				*/
				
				//uncomment to see executed querys at end of response
				/*
				array( 
					'class'=>'CProfileLogRoute', 
					'report'=>'callstack',  // summary or callstack
				),
				*/
				// uncomment the following to show log messages on web pages
				/*
				array(
					'class'=>'CWebLogRoute',
				),
				*/
			),
		),
		
		'clientScript'=>array(
			'class'=>'CAjaxOptimizedClientScript',
		),
		'coreMessages'=>array(
			'basePath'=>null,
		),
		'Randomness'=>array('class'=>'Randomness',),
		"solr" => array(
			"class" => "packages.solr.ASolrConnection",
			"clientOptions" => array(
				"hostname" => "localhost",
				"port" => 8983,
				"path" => "/solr/recipes",
				"login" => "guest",
				"password" => "guest",
				"wt" => "phps",
			),
		),
		"solrIng" => array(
			"class" => "packages.solr.ASolrConnection",
			"clientOptions" => array(
				"hostname" => "localhost",
				"port" => 8983,
				"path" => "/solr/ingredients",
			),
		),
	),

	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'adminEmail'=>'alexis@everycook.org',
		'adminEmailName'=>'Alexis Wiasmitinow',
		//'verificationEmail'=>'alexis@everycook.org',
		//'verificationEmailName'=>'Alexis Wiasmitinow',
		'verificationEmail'=>'support@everycook.org',
		'verificationEmailName'=>'Support',
		'verificationBCCEmail'=>'',
		'verificationRegardsName'=>'Alexis',
		'SMTPMailHost'=>'smtp.gmail.com',
		'SMTPMailUser'=>'',
		'SMTPMailPW'=>'',
		'POPHost'=>'pop.gmail.com',
		'PageType'=>'homepage',
		'isDevice'=>true,
		'localNetwork'=>true,
		'deviceWritePath'=>'/dev/shm/command',
		'deviceReadPath'=>'/dev/shm/status',
		'deviceWriteUrl'=>'/hw/sendcommand.php?command=',
		'deviceReadUrl'=>'/hw/status',
		'FinishedActionId'=>13,
		'PrepareActionId'=>11,
		'syncCredentialsFile'=>'/opt/EveryCook/sync/login_cred',
		'cacheMethode'=>'session', //'session','apc','memcached',
		'stepMinTime'=>10,
		'runSyncCommand'=>'/opt/EveryCook/installSettings everycook_sync_wait',
		'lastSyncDateFile'=>'/opt/EveryCook/sync/lastRecipeSyncDate.txt',
		'twitterConsumerKey'=>'',
		'twitterConsumerSecret'=>'',
		'twitterOauthToken'=>'',
		'twitterOauthTokenSecret'=>'',
	),
);
