<?php

namespace app\modules\product\controllers;

use yii\web\Controller;

/**
 * Default controller for the `Product` module
 */
class DefaultController extends Controller
{
		public function actions()
		{
			return [
				'error' => [
					'class' => 'yii\web\ErrorAction',
				],
				'captcha' => [
					'class' => 'yii\captcha\CaptchaAction',
					'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
				],
			];
		}
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
    	$max = 5;
	    $start = rand(1,$max);
	    $end = rand(1, $max);
	    if($start > $end){
		    $start1 = $end;
		    $end1 = $start;
	    } else {
		    $start1 = $start;
		    $end1 = $end;
	    }


	    echo $start1.' - '.$end1.'<br>';
	    var_dump('');exit;



	    var_dump($array);exit;
        return $this->render('index');
    }
}
