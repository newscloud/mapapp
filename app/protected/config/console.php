<?php
 $config = parse_ini_file(dirname(__FILE__) .'/../../../../secure/config-monitorapp.ini', true);
 require_once dirname(__FILE__) . '/../components/helpers.php'; 
 
// This is the configuration for yiic console application.
// Any writable CConsoleApplication properties can be configured here.
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'My Console Application',

	// preloading 'log' component
	'preload'=>array('log'),

	'modules'=>array(
    'user'=>array(
            # encrypting method (php hash function)
            'hash' => 'md5',

            # send activation email
            'sendActivationMail' => true,

            # allow access for non-activated users
            'loginNotActiv' => false,

            # activate user on registration (only sendActivationMail = false)
            'activeAfterRegister' => false,

            # automatically login from registration
            'autoLogin' => true,

            # registration path
            'registrationUrl' => array('/user/registration'),

            # recovery password path
            'recoveryUrl' => array('/user/recovery'),

            # login form path
            'loginUrl' => array('/user/login'),

            # page after login
            'returnUrl' => array('/user/profile'),

            # page after logout
            'returnLogoutUrl' => array('/user/login'),
        ),		
	),
	// application components
	'components'=>array(
		/* 
		'db'=>array(
			'componentsonnectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
		),
		*/
		// uncomment the following to use a MySQL database
		/* */
		'db'=>array(
			'connectionString' => 'mysql:host='.$config['mysql_host'].';dbname='.$config['mysql_db'],
			'emulatePrepare' => true,
			'username' => $config['mysql_un'],
			'password' => $config['mysql_pwd'],
			'charset' => 'utf8',
			'tablePrefix'=> $config['mysql_tbl_prefix'],
		),
		/* */
		'log'=>array(
			'class'=>'CLogRouter',
			'routes'=>array(
				array(
					'class'=>'CFileLogRoute',
					'levels'=>'error, warning',
				),
			),
		),
	),
);