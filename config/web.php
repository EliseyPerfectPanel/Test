<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';


$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
		    '@api' => dirname(__DIR__) . '/api',
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
        'errorHandler' => [
            'errorAction' => 'v1/rate/error',
        ],
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
            'showScriptName' => true,
            'rules' => [
		            [
			            'class'       =>  'yii\rest\UrlRule',
	                'controller'  => ['v1'],
			            'prefix'  => 'api',
			            'pluralize' => false,
			            'extraPatterns' => [
				            'GET /' => 'rate/index',
				            'POST exchange' => 'rate/exchange',
			            ],
		            ],
            ],
        ],
    ],
		'modules' => [
		    'v1' => [
			    'class' => 'api\modules\v1\Module',
		    ],
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
