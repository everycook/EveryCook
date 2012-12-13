<?php

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
	),

	'modules'=>array(
		'admin',
		// uncomment the following to enable the Gii tool
		
		'gii'=>array(
			'class'=>'system.gii.GiiModule',
			'password'=>'3veryC00k',
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
//			'enableParamLogging'=>true,
//			'enableProfiling'=>true,
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
//			'enableParamLogging'=>true,
//			'enableProfiling'=>true,
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
					'levels'=>'error, warning',
				),
				//uncomment to see executed querys
				/*
				array( 
					'class'=>'CProfileLogRoute', 
					'report'=>'callstack',  /" summary or callstack
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
		'verificationBCCEmail'=>''/*'wiasmitinow@gmail.com'*/,
		'verificationRegardsName'=>'Alexis',
		'SMTPMailHost'=>'smtp.gmail.com',
		'SMTPMailUser'=>'support@everycook.org',
		'SMTPMailPW'=>'5upp0r77e4m',
		'POPHost'=>'pop.gmail.com',
		'PageType'=>'homepage',
		'isDevice'=>true,
		'localNetwork'=>true,
		'deviceWritePath'=>'/dev/ttyACM0',
		'deviceReadPath'=>'/var/www/db/hw/status',
		'deviceWriteUrl'=>'/EveryCook/sendcommand.php?command=',
		'deviceReadUrl'=>'db/hw/status',
	),
);
