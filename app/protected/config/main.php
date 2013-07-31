<?php
  $config = parse_ini_file(dirname(__FILE__) .'/../../../../secure/config-monitorapp.ini', true);
  if ($config['env']<>'live') 
    defined('YII_DEBUG') or define('YII_DEBUG',true);
  require_once dirname(__FILE__) . '/../components/helpers.php'; 
  include dirname(__FILE__) . '/../../../vendor/autoload.php'; // composer autoload
// This is the main Web application configuration. Any writable
// CWebApplication properties can be configured here.
#Yii::setPathOfAlias('bootstrap', dirname(FILE).'/../extensions/bootstrap');
$options = array(
  
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'ListApp',

	// preloading 'log' component
	'preload'=>array(
	  'log',
	  'bootstrap',
	  'mailgun'
	  ),

	// autoloading model and component classes
	'import'=>array(
		'application.models.*',
		'application.components.*',
    'application.modules.user.models.*',
    'application.modules.user.components.*',
    'application.modules.yiiauth.components.*',
  	),

	'modules'=>array(
'auth' => array(
  'strictMode' => true, // when enabled authorization items cannot be assigned children of the same type.
  'userClass' => 'User', // the name of the user model class.
  'userIdColumn' => 'id', // the name of the user id column.
  'userNameColumn' => 'username', // the name of the user name column.
  'appLayout' => 'application.views.layouts.main', // the layout used by the module.
  'viewDir' => null, // the path to view files to use with this module.
),
'user'=>array(            
            'hash' => 'md5', # encrypting method (php hash function)
            'sendActivationMail' => true, # send activation email
            'loginNotActiv' => false, # allow access for non-activated users
            # activate user on registration (only sendActivationMail = false)
            'activeAfterRegister' => false,
            'autoLogin' => true, # automatically login from registration
            'registrationUrl' => array('/user/registration'), // registration path
            'recoveryUrl' => array('/user/recovery'),   // # recovery password path
            'loginUrl' => array('/user/login'), // login form path
            'returnUrl' => array('/place/index'), //page after login
            'returnLogoutUrl' => array('/'), // page after logout
        ),		
        // hybrid auth module
        'yiiauth'=>array(
                'userClass'=>'User', //the name of your Userclass
                'config'=>array(
                "base_url" => "http://yourdomain.com/hybridauth/", 
                "providers" => array ( 
                "Facebook" => array ( 
                "enabled" => true,
                "keys"    => array ( "id" => "sample-id", "secret" => "sample-secret" ),
        // A comma-separated list of permissions you want to request from the user. See the Facebook docs for a full list of available permissions: http://developers.facebook.com/docs/reference/api/permissions.
                 "scope"   => "email,user_about_me,user_website", 
        // The display context to show the authentication page. Options are: page, popup, iframe, touch and wap. Read the Facebook docs for more details: http://developers.facebook.com/docs/reference/dialogs#display. Default: page
                                        "display" => "popup" 
                                ),
                        ),

                        // if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
                        "debug_mode" => false,

                        "debug_file" => "",
                ),
                        )	
	),

	// application components
	'components'=>array(
    'format'=>array(
           'class'=>'ext.timeago.TimeagoFormatter',
       ),
      's3'=>array(
            'class'=>'ext.s3.ES3',
            'aKey'=>$config['aws_s3_access'], 
            'sKey'=>$config['aws_s3_secret'],
        ),  
    'phpThumb'=>array(
        'class'=>'ext.EPhpThumb.EPhpThumb',
        'options'=>array(),
    ),	  
		'session' => array(
                'timeout' => 86400,
            ),
		'user'=>array(
			// enable cookie-based authentication
			'allowAutoLogin'=>true,
			'autoRenewCookie' => true,
      'authTimeout' => 31557600,
		),
    'bootstrap' => array(
	    'class' => 'ext.bootstrap.components.Bootstrap',
	    'responsiveCss' => true,
	),	
		
  'authManager' => array(
    'class'=>'auth.components.CachedDbAuthManager',
     'cachingDuration'=>3600,
      'behaviors' => array(
        'auth' => array(
       'class' => 'auth.components.AuthBehavior',
        'admins'=>array('admin'), // users with full access
      ), ),
      ),	
		'urlManager'=>array(
    			'urlFormat'=>'path',
    			'showScriptName'=>false,
    			'caseSensitive'=>false,
    			'rules'=>array(
    				'<controller:\w+>/<id:\d+>'=>'<controller>/view',
    				'<controller:\w+>/<action:\w+>/<id:\d+>'=>'<controller>/<action>',
    				'<controller:\w+>/<action:\w+>'=>'<controller>/<action>',
    				'/hybridauth' => '/hybridauth',
    				'' => 'site/index'
    			),
    		),
		'db'=>array(
			'connectionString' => 'mysql:host='.$config['mysql_host'].';dbname='.$config['mysql_db'],
			'emulatePrepare' => true,
			'username' => $config['mysql_un'],
			'password' => $config['mysql_pwd'],
			'charset' => 'utf8',
			'tablePrefix'=> $config['mysql_tbl_prefix'],
		),
		/* */
		'user'=>array(
            // enable cookie-based authentication
            'class' => 'WebUser',
            'class' => 'auth.components.AuthWebUser',
        ),
		'errorHandler'=>array(
			// use 'site/error' action to display errors
			'errorAction'=>'site/error',
		),
    'curl' => array(
      'class' => 'ext.Curl',
      //'options' => array(),
      ),
  ),
	// application-level parameters that can be accessed
	// using Yii::app()->params['paramName']
	'params'=>array(
		// this is used in contact page
		'env'=> $config['env'],
		'base_root'=> $config['base_root'], // used for localhost
		'mail_domain'=> $config['mail_domain'],
		'superuser'=>$config['superuser'],
		'adminEmail'=>'admin@yourdomain.com',
		'supportEmail'=>$config['support_email'],
		'postsPerPage'=> 10,
    'mailgun'=> array(
      'api_key'=> $config['mailgun_api_key'],
        'api_url' => $config['mailgun_api_url']
    ),
	),
);
// configure logging
if ($config['env'] <> 'live') {
  $options['components']['fixture']=array(
		'class'=>'system.test.CDbFixtureManager',
	);
  $options['components']['log']=
		array(
  			'class'=>'CLogRouter',
  			'routes'=>array(
  			  array('class'=>'CWebLogRoute',
  			  'levels'=>'error,warning,info,trace',
  			  ),
    	  ));
    	  // enable gii
    $options['modules']['gii'] =
    		// uncomment the following to enable the Gii tool
    		// path to gii = /gii/default/login
    		array(
    			'class'=>'system.gii.GiiModule',
    			'password'=>$config['gii_pwd'],
    			'generatorPaths' => array(
              'bootstrap.gii'
           ),
    			// If removed, Gii defaults to localhost only. Edit carefully to taste.
    			'ipFilters'=>array('127.0.0.1','::1'),
    		);    	  
} else {
  $options['components']['log']=
		array(
  			'class'=>'CLogRouter',
  			'routes'=>array(
          array('class'=>'CFileLogRoute','levels'=>'error, warning, info')
    	  ));  
}
return $options;

