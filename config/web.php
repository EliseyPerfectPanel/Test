<?php

use yii\web\UrlNormalizer;

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';


$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => [
//    	'log',
			'api\modules\v1\Bootstrap'
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
		    '@api' => dirname(__DIR__) . '/api',
//	      '@modules' => dirname(__DIR__) . '/modules',
    ],
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'fD34HHddsettry',
		        'parsers' => [
			        'application/json' => 'yii\web\JsonParser',
		        ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'api\modules\v1\models\User',
            'enableAutoLogin' => true,
	          'enableSession' => true
        ],
//        'errorHandler' => [
//            'errorAction' => 'v1/rate/error',
//        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
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
        'db' => $db,
        'urlManager' => [
            'enablePrettyUrl' => true,
	          'enableStrictParsing' => true,
            'showScriptName' => false,
		        'normalizer' => [
			        'class' => 'yii\web\UrlNormalizer',
			        'action' => UrlNormalizer::ACTION_REDIRECT_PERMANENT,
		        ],
            'rules' => [
//		            [
//			            'class'       =>  'yii\rest\UrlRule',
//	                'controller'  => ['v1'],
//			            'prefix'  => 'api',
//			            'pluralize' => false,
//			            'extraPatterns' => [
//			            	'GET /' => '',
//				            'GET /' => 'rate/index',
//				            'POST exchange' => 'rate/exchange',
//			            ],
//		            ],
            ],
        ],
    ],
		'modules' => [
		    'v1' => [
			    'class' => 'api\modules\v1\Module',
		    ],
				'product' => [
					'class' => 'app\modules\product\Product',
				]
		],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        // uncomment the following to add your IP if you are not connecting from localhost.
        //'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
