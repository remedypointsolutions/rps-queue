<?php

namespace rps\sqs\models;

use Yii;

/**
 * This is the model class for table "queue_order".
 *
 * @property integer $id
 * @property string $order_id
 * @property string $status
 * @property string $queue_name
 * @property string $type
 */
class BaseQueueOrder extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'queue_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['order_id', 'queue_name'], 'required'],
            [['status'], 'string'],
            [['order_id', 'queue_name', 'type'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'order_id' => 'Order ID',
            'status' => 'Status',
            'queue_name' => 'Queue Name',
            'type' => 'Type',
        ];
    }
}
