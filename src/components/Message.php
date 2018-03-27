<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 8/17/16
 * Time: 9:49 PM
 */

namespace rps\sqs\components;

use yii\helpers\Json;

class Message
{
    /**
     * The receipt handle from SQS, used to identify the message when interacting with the queue
     *
     * @var string
     */
    public $receipt_handle;

    public $data;

    public $operation;

    public function __construct($data, $receipt_handle = '')
    {
        // If data is a json string, decode it into an assoc array
        if (is_string($data)) {
            $this->data = json_decode($data, true);
        } else {
            $this->data = $data;
        }

        // Assign the data values and receipt handle to the object
        $this->receipt_handle = $receipt_handle;
    }

    public function asJson()
    {
        return Json::encode($this->data);
    }
}