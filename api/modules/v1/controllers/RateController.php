<?php

namespace api\modules\v1\controllers;

use yii;
use yii\rest\Controller;
use yii\helpers\Html;
use yii\base\ErrorException;
use yii\filters\auth\HttpBearerAuth;

class RateController extends Controller
{
		private $commission     = 2; //-- percents
		private $defaultResponse = [
			'status'      => 'error',
			'code'        => 403,
			'message'     => 'Invalid Token'
		];

		public function init() {
			parent::init();
		}
		public function behaviors()
		{
			//-- аутентификатор + ограничение только для 2х методов, остальные на error
			$behaviors = parent::behaviors();
			$behaviors['authenticator'] = [
				'class' =>  HttpBearerAuth::class,
				'only' => ['index', 'exchange']
			];
			return $behaviors;
		}

		private function tickerRequest(){
			$request = $this->defaultResponse;
			try {
				$data = file_get_contents('https://blockchain.info/ticker');
				if($data === FALSE){
					throw new ErrorException('blockchain.info server error!');
				}
				$data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
				$request = [
					'status' => 'success',
					'code'   => 200,
					'data' => $data
				];
			} catch(yii\base\ErrorException $e){
				$request = [
					'status'      => 'error',
					'code'        => 403,
					'message'     => 'Invalid Token'
//					'message'     => $e->getMessage()
				];
			}
			catch(\JsonException $e){
//				$request = [
//					'status'      => 'error',
//					'code'        => 403,
//					'message'     => 'Json Syntax Error'
//				];
			}
			return $request;
		}
		//-- конвертнем сразу весь массив, не ну а чо
		private function convertByCommission(&$data = []){
			foreach ($data as &$item){
				$item = number_format($item['buy'] + ($item['buy']*($this->commission/100)), 2, '.', '');
			}
		}
		public function actionIndex($currency = ''){
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

			$request = $this->tickerRequest();
			if($request['code'] != 200){
				return $request;
			}

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
