<?php

use yii\db\Migration;

/**
 * Handles the creation for table `queue_order_table`.
 */
class m160916_134757_create_queue_order_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tn = '{{%queue_order}}';

        $this->createTable($tn,[
            'id' => $this->primaryKey(),
            'queue_name' => $this->string()->notNull(),
            'order_id' => $this->string()->notNull(),
            'status' => 'enum("new","pending","processed")'
        ]);

        $this->createIndex('order_id', $tn, 'order_id', false);
        $this->createIndex('queue_status', $tn, ['queue_name', 'status'], false);

        return true;
    }
    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('queue_order_table');
    }
}
