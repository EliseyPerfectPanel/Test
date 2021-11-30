<?php

namespace api\modules\v1\models;

use yii\httpclient\Client;
use yii\httpclient\Exception;
use yii\httpclient\Response;

class BlockchainTicker extends Client {

	public function validate(Response $response){
		if($response->isOk){
			if(empty($response->data)){
				throw new Exception('Empty response');
			}
			if(!is_array($response->data)){
				throw new Exception('Not array');
			}
			if(count($response->data) == 0){
				throw new Exception('Json structure error');
			}

			foreach ($response->data as $key => $value){
				if(strlen($key) != 3 || !is_array($value) || empty($value['15m']) || empty($value['last']) || empty($value['buy']) || empty($value['sell']) || empty($value['symbol']) || $key != $value['symbol']){
					throw new Exception('Json structure error');
				}
			}
		} else {
			throw new Exception('Response error');
		}
	}

	public function afterSend($request, $response)
	{
		parent::afterSend($request, $response);
		$this->validate($response);
	}



}