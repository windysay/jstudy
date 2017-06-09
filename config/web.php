<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'Iperaperaへようこそ！',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'defaultRoute' => 'site', //默认控制器
    'language' => 'en', //语言
    'charset'=>'UTF-8',
    'timeZone'=>'Asia/Chongqing',//时区设置
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'm3-4H42sNEY9USbX_BU3csCqVXPEIlTx',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'memcache' => [
	        'class' => 'yii\caching\MemCache',
	        'servers' => [
		        [
			        'host' => '127.0.0.1',
			        'port' => 11211,
			        'weight' => 100,
		        ],
	        ],
        ],
        'user' => [//学生会员
            'identityClass' => 'app\modules\student\models\Student', // User must implement the IdentityInterface
            'enableAutoLogin' => true,
            'loginUrl' => ['account/choice-login'],//如何没有登录就跳转到这个链接
//            'returnUrl'=>Yii::$app->request->referrer,//登录后跳转的网址
            'idParam' => '__user'
        ],
        'admin' => [//管理员
            'class'=> '\yii\web\User',
            'identityClass' => 'app\modules\admin\models\Admin',
            'enableAutoLogin' => true,
            'loginUrl' => ['account/admin-login'],//如何没有登录就跳转到这个链接
            'returnUrl'=>['admin/site/index'],//登录后跳转的网址
            'idParam' => '__admin'
        ],
        'teacher' => [//老师
            'class'=> '\yii\web\User',
            'identityClass' => 'app\modules\teacher\models\Teacher',
            'enableAutoLogin' => true,
            'loginUrl' => ['account/teacher-login'],//如何没有登录就跳转到这个链接
            'returnUrl'=>['teacher/student/index'],//登录后跳转的网址
            'idParam' => '__teacher'
        ],
        'urlManager'=>[//
        	'class' => 'yii\web\UrlManager',
	        'enablePrettyUrl' => true,
	        'showScriptName' => false,
            'hostInfo'=>'http://jstudy.com/',//可以自己指定目录
            'baseUrl'=>'http://jstudy.com/',
//            'hostInfo'=>'http://www.iperapera.com',//可以自己指定目录
//            'baseUrl'=>'http://www.iperapera.com',
            //'suffix'=>'.html',  //
	        //路由管理
	        'rules' => [
	        ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
		'mailer' => [
		        'class' => 'yii\swiftmailer\Mailer',
		        'viewPath' => 'app/mail',
		        'useFileTransport' => false,
		        'transport' => [
		            'class' => 'Swift_SmtpTransport',
		            'host' => 'smtp.163.com',
		            'username' => 'iperapera@163.com',
		            'password' => 'cheng00',
		            'port' => '25',
		            'encryption' => 'tls',
		        ],
		        'messageConfig'=>[
		        'charset'=>'UTF-8',
		        		'from'=>['iperapera@163.com'=>'IPERAPERAシステムサービス']
               ],
		    ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
    	
        'db' => require(__DIR__ . '/db.php'),
    ],
    'params' => $params,
      
    'modules' => [         
        'admin' => [              
            'class' => 'app\modules\admin\Module',           
        // ... 模块其他配置 ... 
        ],    
        'teacher' => [              
            'class' => 'app\modules\teacher\Module',           
        // ... 模块其他配置 ... 
        ],    
        'student' => [              
            'class' => 'app\modules\student\Module',           
        // ... 模块其他配置 ... 
        ],    
    ], 
	
    
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';//网页最下面的调试版块
    $config['modules']['debug'] = [//网页最下面的调试版块
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;