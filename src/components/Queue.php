<?php
/**
 * Created by PhpStorm.
 * User: igor
 * Date: 8/17/16
 * Time: 9:22 PM
 */

namespace rps\sqs\components;

use Aws\Sqs\SqsClient;
use yii\base\Exception;

class Queue
{
    public $name;

    public $url;

    protected $credentials;

    protected $logCategory = 'sqs';

    /** @var SqsClient $client */
    protected $client;

    public function __construct($name, $url = null, $logCategory = null)
    {
        $this->setName($name);
        $this->credentials = $this->getCredentials();

        $this->client = new SqsClient($this->credentials);

        if ($logCategory) {
            $this->logCategory = $logCategory;
        }

        $this->log('Queue::__construct(), name=' . $name);

        if ($url) {
            $this->url = $url;
        } else {
            $this->getUrl();
        }
    }

    /**
     * Sends a message to SQS using a JSON output from a given Message object
     *
     * @param string $type Operation type
     * @param Message $message  A message object to be sent to the queue
     * @throws Exception
     */
    public function send(Message $message)
    {
        try {
            $this->log('Queue::send()');

            // Send the message
            $this->client->sendMessage([
                'QueueUrl' => $this->url,
                'MessageBody' => $message->asJson(),
            ]);

            $this->log('Queue::send(), ok');

        } catch (\Exception $e) {
            throw new Exception('Error sending message to queue ' . $e->getMessage());
        }
    }

    /**
     * Receives a message from the queue and puts it into a Message object
     *
     * @throws Exception
     * @return bool|Message  Message object built from the queue, or false if there is a problem receiving message
     */
    public function receive()
    {
        $message = false;

        try {
            $this->log('Queue::receive()');

            // Receive a message from the queue
            $result = $this->client->receiveMessage([
                'QueueUrl' => $this->url
            ]);

            if ($result['Messages']) {
                // Get the message and return it
                $m = array_pop($result['Messages']);
                $message = new Message($m['Body'], $m['ReceiptHandle']);
            }

            $this->log('Queue::receive(), ReceiptHandle=' . (isset($m['ReceiptHandle']) ? $m['ReceiptHandle'] : 'null'));

        } catch (\Exception $e) {
            throw new Exception('Error receiving message from queue ' . $e->getMessage());
        }

        return $message;
    }

    /**
     * Deletes a message from the queue
     *
     * @param Message $message
     * @throws Exception
     */
    public function delete(Message $message)
    {
        try {
            $this->log('Queue::delete(), ReceiptHandle=' . $message->receipt_handle);

            // Delete the message
            $this->client->deleteMessage([
                'QueueUrl' => $this->url,
                'ReceiptHandle' => $message->receipt_handle
            ]);

            $this->log('Queue::delete(), ok');

        } catch (\Exception $e) {
            throw new Exception('Error deleting message from queue ' . $e->getMessage());
        }
    }

    /**
     * Releases a message back to the queue, making it visible again
     *
     * @param Message $message
     * @throws Exception
     */
    public function release(Message $message)
    {
        try {
            $this->log('Queue::release(), ReceiptHandle=' . $message->receipt_handle);

            // Set the visibility timeout to 0 to make the message visible in the queue again straight away
            $this->client->changeMessageVisibility([
                'QueueUrl' => $this->url,
                'ReceiptHandle' => $message->receipt_handle,
                'VisibilityTimeout' => 0
            ]);
            $this->log('Queue::release(), ok');

        } catch (\Exception $e) {
            throw new Exception('Error releasing job back to queue ' . $e->getMessage());
        }
    }

    /**
     * Set Queue name
     *
     * @param string $name
     * @return string
     */
    public function setName($name)
    {
        return $this->name = $name;
    }

    /**
     * Gets url by queue  name
     *
     * @throws Exception
     * @return string
     */
    protected function getUrl()
    {
        try {
            $this->log('Queue::getUrl()');
            $result = $this->client->getQueueUrl(['QueueName' => $this->name])->get('QueueUrl');
            $this->url = $result->get('QueueUrl');
            $this->log('Queue::getUrl(), url=' . $this->url);

        } catch (\Exception $e) {
            throw new Exception('Error getting the queue url ' . $e->getMessage());
        }

        return $this->url;
    }

    /**
     * SQS credentials
     *
     * @return array
     */
    protected function getCredentials()
    {
        $params = \Yii::$app->params['queue'][$this->name];

        return [
            'region' => $params['config']['region'],
            'version' => $params['config']['version'],
            'credentials' => [
                'key'    => $params['config']['key'],
                'secret' => $params['config']['secret'],
            ]
        ];
    }

    /**
     * @param Message[] $messages
     */
    public function sendAll($messages)
    {
        foreach($messages as $message) {
            $this->send($message);
        }
    }

    protected function log($message)
    {
        \Yii::info($message, $this->logCategory);
    }
}