<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\product\models\Product */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Products', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="product-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Изменить', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Удалить', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'title',
            'url:url',
            'description:ntext',
            'image',
            'article',
	        [
		        'attribute' => 'price',
		        'format' => 'raw',
		        'value' => function ($data) {
			        return $data->price.' руб.';
		        },
	        ],
	        [
		        'attribute' => 'status',
		        'format' => 'raw',
		        'value' => function ($data) {
                    return $data->status==0?'Скрыто':'Опубликовано';
		        },
	        ],
            'created:datetime',
            'changed:datetime',
        ],
    ]) ?>

</div>
