<?php

namespace api\modules\v1;
use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
	/**
	 * @inheritdoc
	 */
	public function bootstrap($app)
	{
		$app->getUrlManager()->addRules(
			[
				'GET api/v1'  => 'v1/rate/index',
				'POST api/v1' => 'v1/rate/convert'


//				[
//					'class'       =>  'yii\rest\UrlRule',
//					'controller'  => ['rate'],
//					'prefix'  => 'api',
//					'pluralize' => false,
//					'extraPatterns' => [
//						'GET /' => 'rate/index',
//						'POST exchange' => 'rate/exchange',
//					],
//				],
				// объявление правил здесь
//				'' => 'site/default/index',
//				'<_a:(about|contacts)>' => 'site/default/<_a>'
//				'hello' => 'v1/rate/index'
			]
		);
	}
}