<?php

namespace app\modules\product\models;

use Yii;

/**
 * This is the model class for table "product".
 *
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string $description
 * @property string $image
 * @property string $article
 * @property int $price
 * @property int $status
 * @property int $weight
 * @property int $created
 * @property int $changed
 *
 * @property ProductCategories[] $productCategories
 */
class Product extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'url', 'description', 'image', 'article', 'price', 'status', 'weight', 'created', 'changed'], 'required'],
            [['description'], 'string'],
            [['price', 'status', 'weight', 'created', 'changed'], 'integer'],
            [['title', 'url', 'image', 'article'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'url' => 'Url',
            'description' => 'Description',
            'image' => 'Image',
            'article' => 'Article',
            'price' => 'Price',
            'status' => 'Status',
            'weight' => 'Weight',
            'created' => 'Created',
            'changed' => 'Changed',
        ];
    }

    /**
     * Gets query for [[ProductCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProductCategories()
    {
        return $this->hasMany(ProductCategories::className(), ['product_id' => 'id']);
    }
}
