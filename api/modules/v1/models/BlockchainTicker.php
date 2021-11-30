<?php

namespace api\modules\v1\models;

use yii\httpclient\Client;
use yii\httpclient\Exception;

class Request {
	protected $_response  = null;
	protected $_url       = null;


	public function __construct($url)
	{
		$client = new Client();
		$response = $client->createRequest()
			->setMethod('GET')
			->setUrl('https://bl1ockchain.info/ticker')
			->send();
	}

	public function setUrl($){

	}

}