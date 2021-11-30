<?php

namespace api\modules\v1\controllers;

use api\modules\v1\models\BlockchainTicker;
use yii;
use yii\rest\Controller;
use yii\helpers\Html;
use yii\filters\auth\HttpBearerAuth;


class RateController extends Controller
{
		private $commission         = 2; //-- percents
		protected $_defaultResponse = [
			'status'      => 'error',
			'code'        => 403,
			'message'     => 'Invalid Token'
		];


		public function behaviors()
		{
			$behaviors = parent::behaviors();
			$behaviors['authenticator'] = [
				'class' =>  HttpBearerAuth::class,
				'only' => ['index', 'exchange']
			];
//			$behaviors['verbs'] = [
//				'class' => \yii\filters\VerbFilter::class,
//				'actions' => [
//					'index'  => ['GET', 'POST'],
////					'rate' => ['POST'],
//				]
//			];
			return $behaviors;
		}

		//-- конвертнем сразу весь массив, не ну а чо
		private function convertByCommission(&$data = []){
			foreach ($data as &$item){
				$item = number_format($item['buy'] + ($item['buy']*($this->commission/100)), 2, '.', '');
			}
		}
		public function actionIndex($method){
			$request = Yii::$app->request;

			var_dump($method);
			echo '</br>';
			var_dump($request->method);exit;


			if($method == 'convert'){
			}



			$currency = Html::encode($currency);
			//-- тут бы еще регулярочкой подчистить все кроме "," и "A-Z"
			if(strlen($currency)>=3){
				$currency = explode(',', strtoupper($currency));
				if(count($currency) == 0){
					$currency = '';
				}
			} else {
				$currency = '';
			}

			//-- отправляем запрос + валидация json и его структуры
			try {
				$bct = new BlockchainTicker();
				$response = $bct->createRequest()
					->setMethod('GET')
					->setUrl('https://blockchain.info/ticker')
					->send();


			} catch (yii\httpclient\Exception $e) {
				var_dump($e);exit;
				return $this->_defaultResponse;
			}
			var_dump($response->data);exit;


			//-- получаем обрабатываем
			foreach ($request['data'] as &$item){
				//-- если есть установленые валюты отфильтруем нужное
				if(!empty($currency) && !in_array($item['symbol'], $currency)){
					unset($request['data'][$item['symbol']]);
					continue;
				}
			}

			//-- если массив опустел
			if(empty($request['data'])){
				$request = $this->defaultResponse;
			} else {
				//-- тцт все ок сортировочка
				$this->convertByCommission($request['data']);
				asort($request['data']);
			}



			return $this->asJson($request);
		}

		public function actionConvert($method, $currency_from, $currency_to, $value){

			$request = Yii::$app->request;

			if($method != 'convert'){

			}

		}



		public function actionExchange($currency_from = null, $currency_to = null, $value = null){
			$value = floatval($value);
			if(
					$currency_from === null
					|| strlen($currency_from) !== 3
					|| $currency_to === null
					|| strlen($currency_to) !== 3
					|| $value === null
					|| empty($value)
					|| $value < 0
					|| !is_float($value)
					|| $currency_from == $currency_to){
						return $this->asJson($this->defaultResponse);
			}
			$currency_from  = strtoupper($currency_from);
			$currency_to    = strtoupper($currency_to);


			$request = $this->tickerRequest();

			if(
				$request['code'] != 200
				|| empty($request['data'])
				|| ($currency_from != 'BTC' && !isset($request['data'][$currency_from]))
				|| ($currency_to != 'BTC' && !isset($request['data'][$currency_to]))
			){
				return $this->defaultResponse;
			}
			//--
			$data = [
				'status'  => 'success',
				'code'    => 200,
				'data' => [
					'currency_from'   => $currency_from,
					'currency_to'     => $currency_to,
					'value'           => $value,
					'converted_value' => null,
					'rate'            => $this->commission
				]
			];


			if($currency_from == 'BTC'){
				//-- обрезание массива до 1 валюты
				$request['data'] = [
					$currency_to => $request['data'][$currency_to]
				];
				$this->convertByCommission($request['data']);

				$data['data']['converted_value'] = number_format($value*$request['data'][$currency_to], 2, '.', '');
			} else {
				//-- обрезание массива до 1 валюты
				$request['data'] = [
					$currency_from => $request['data'][$currency_from]
				];
				$this->convertByCommission($request['data']);

				$data['data']['converted_value'] = number_format($value/$request['data'][$currency_from], 10, '.','');
			}
			return $this->asJson($data);
		}
		public function actionError(){
			return $this->asJson($this->defaultResponse);
		}

}
