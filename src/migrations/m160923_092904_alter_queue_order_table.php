<?php

use yii\db\Migration;

class m160923_092904_alter_queue_order_table extends Migration
{
    public function up()
    {
        $tn = '{{%queue_order}}';
        $this->dropIndex('queue_status', $tn);
        $this->createIndex('queue_status', $tn, ['queue_name', 'order_id', 'status'], false);
        $this->createIndex('queue_type', $tn, ['queue_name', 'order_id', 'type'], false);

        return true;
    }

    public function down()
    {
        echo "m160923_092904_alter_queue_order_table cannot be reverted.\n";

        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
