<?php
/**
 * Created by PhpStorm.
 * User: sullenboom
 * Date: 27/08/15
 * Time: 20:20
 */

namespace DetailTest\Mock;

use Detail\Notification\Sender;
use Detail\Notification\SenderManagerInterface;

class Senders implements SenderManagerInterface
{
    /**
     * Array with Sender\SenderInterface
     * @var array
     */
    protected $senders = [];

    public function __construct(array $senders)
    {
        //should here also add a setter
        $this->senders = $senders;
    }

    /**
     * @param string $type
     * @return boolean
     */
    public function hasSender($type)
    {
        return isset($this->senders[$type]);
    }

    /**
     * @param string $type
     * @param array $options
     * @return Sender\SenderInterface
     */
    public function getSender($type, $options = array())
    {
        // $options options not needed for the mock

        if (!$this->hasSender($type)) {
            throw new \InvalidArgumentException($type . ' type not exists.');
        }

        return $this->senders[$type];
    }
}