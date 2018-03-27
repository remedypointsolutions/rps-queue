<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 8/18/16
 * Time: 4:25 PM
 */

namespace rps\sqs\models;


class QueueOrder extends BaseQueueOrder
{
    const STATUS_NEW = 'new';
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSED = 'processed';

    public function isNew()
    {
        return $this->status == self::STATUS_NEW;
    }

    public function save($runValidation = true, $attributeNames = null)
    {
        $result = parent::save($runValidation, $attributeNames);
        return $result;
    }
}