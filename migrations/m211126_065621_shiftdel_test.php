<?php

use yii\db\Schema;
use yii\db\Migration;

/**
 * Class m211126_065621_shiftdel_test
 */
class m211126_065621_shiftdel_test extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
	    $tableOptions = null;
	    $productCount = 20;
	    $categoryCount = 5;

	    //Опции для mysql
	    if ($this->db->driverName === 'mysql') {
		    $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
	    }

	    $this->createTable('product', [
//				категория(id)
				'id'      => $this->primaryKey()->unsigned(),
		    'title'   => $this->string()->notNull(),
		    'url'     => $this->string()->notNull(),
		    'description' => $this->text()->notNull(),
		    'image'       => $this->string()->notNull(),
		    'article'     => $this->string()->notNull(),
		    'price'       => $this->integer()->notNull()->unsigned(),
		    'status'      => $this->tinyInteger()->notNull(),
		    'weight'      => $this->tinyInteger()->notNull(),
		    'created'     => $this->integer(11)->notNull(),
		    'changed'     => $this->integer(11)->notNull()
	    ], $tableOptions);

	    for ($i = 1; $i <= $productCount; $i++){
		    $this->insert('product', [
		    	'id'    => $i,
			    'title' => 'Product '.$i,
			    'url'     => 'product/'.$i,
			    'description' => 'Test description for '.$i,
			    'image'       => 'images/images'.$i.'.jpg',
			    'article'     => '1.1000.'.$i,
			    'price'       => 10*$i,
			    'status'      => 1,
			    'weight'      => $i,
			    'created'     => time(),
			    'changed'     => time()
		    ]);
	    }

	    $this->createTable('category', [
		    'id' => $this->primaryKey()->unsigned(),
		    'title' => $this->string()->notNull(),
		    'created'     => $this->integer(11)->notNull(),
		    'changed'     => $this->integer(11)->notNull()
	    ], $tableOptions);

	    for ($i = 1; $i <= $categoryCount; $i++){
		    $this->insert('category', [
			    'id'    => $i,
			    'title' => 'Категория '.$i,
			    'created' => time(),
			    'changed' => time()
		    ]);
	    }

	    $this->createTable('product_categories', [
		    'id'          => $this->primaryKey()->unsigned(),
		    'product_id'  => $this->integer()->notNull()->unsigned(),
		    'category_id' => $this->integer()->notNull()->unsigned()
	    ], $tableOptions);

	    $this->addForeignKey(
		    'fk-product-id',
		    'product_categories',
		    'product_id',
		    'product',
		    'id',
		    'CASCADE'
	    );
	    $this->addForeignKey(
		    'fk-category-id',
		    'product_categories',
		    'category_id',
		    'category',
		    'id',
		    'CASCADE'
	    );


	    $max = 5;
	    for ($i = 1; $i <= $productCount; $i++){ //--прописать удаление по ключу товара


		    $start = rand(1,$max);
		    $end = rand(1, $max);
		    if($start > $end){
			    $start1 = $end;
			    $end1 = $start;
		    } else {
			    $start1 = $start;
			    $end1 = $end;
		    }
		    for ($j = $start1; $j <= $end1; $j++) {
			    $this->insert('product_categories', [
				    'product_id' => $i,
				    'category_id' => $j
			    ]);
		    }
	    }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
	    $this->dropTable('product');
	    $this->dropTable('category');
	    $this->dropTable('product_categories');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211126_065621_shiftdel_test cannot be reverted.\n";

        return false;
    }
    */
}
