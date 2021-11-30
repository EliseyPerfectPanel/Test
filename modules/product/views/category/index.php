<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Categories';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Category', ['create'], ['class' => 'btn btn-success']) ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            'id',
	        [
                'attribute' => 'title',
                'format' => 'raw',
                'value' => function ($data) {
			        return Html::tag('a', $data->title, ['href' => Url::to(['category/view', 'id' => $data->id])]);
		        },
            ],
            ['attribute' => 'created', 'format' => ['date', 'php:Y-m-d H:i:s']],
	        ['attribute' => 'changed', 'format' => ['date', 'php:Y-m-d H:i:s']],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
