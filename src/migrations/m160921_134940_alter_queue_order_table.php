<?php

use yii\db\Migration;

class m160921_134940_alter_queue_order_table extends Migration
{
    public function up()
    {
        $tn = '{{%queue_order}}';
        $this->addColumn($tn, 'type', $this->string()->defaultValue('add'));
        $this->dropIndex('queue_status', $tn);
        $this->createIndex('queue_status', $tn, ['queue_name', 'status'], false);
        return true;
    }

    public function down()
    {
        echo "m160921_134940_alter_queue_order_table cannot be reverted.\n";

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
