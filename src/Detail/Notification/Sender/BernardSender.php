<?php

namespace Detail\Notification\Sender;

use Bernard\Producer;
use Bernard\QueueFactory\PersistentFactory;
use Bernard\Message\DefaultMessage;

use Detail\Notification\Call;
use Detail\Notification\Exception;

/**
 * Very Simple Sender using Bernard Queueing
 */
class BernardSender extends BaseSender
{

    /**
     * Parameter key for message type to be sent to queue
     */
    const MESSAGE_TYPE    = 'message-type';

    /**
     * Array of string keys for required parameters
     * @var array
     */
    protected $requiredParams = array(
        self::MESSAGE_NAME
    );
    /**
     * Bernard Message Queue Factory
     * @var PersistentFactory
     */
    protected $BernardQueueFactory;

    /**
     * Producer for messages to be sent to message queue
     * @var Producer
     */
    protected $BernardProducer;

    /**
     * Sender ctor.
     * @param PersistentFactory $bernardQueueFactory Factory to use for creating message producers
     */
    public function __construct(PersistentFactory $bernardQueueFactory)
    {
        $this->BernardQueueFactory = $bernardQueueFactory;
        $this->BernardProducer = new Producer($this->BernardQueueFactory);

    }

    /**
     * Sends a notification payload utilizing a bernard message queue
     * @param  array  $payload message payload
     * @param  array  $params  optional parameters for sending
     * @return Call          the Call created for the message that was sent
     */
    public function send(array $payload, array $params = array())
    {

        $params = $this->validateParams($params);

        $getParam = function ($key, $default = null) use ($params) {
            return array_key_exists($key, $params) ? $params[$key] : $default;
        };

        $messageType = $getParam(self::MESSAGE_TYPE);

        // create a bernard default message
        $msg = new DefaultMessage(
            $messageType,
            $payload
        );

        // send the message to the message queue using the message
        // producer
        $BernardProducer->produce($msg);

        // the sent message can be consumed by a consumer
        // or you can do whatever you need / want to do
        // aside form this basic stuff here

        /** @todo currently no error can be caught because bernard production returns nothing. Is there a better way to do this? */
        $call = new Call();

        return $call;
    }
}
