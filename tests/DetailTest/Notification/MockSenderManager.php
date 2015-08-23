<?php

namespace DetailTest\Notification;

use Detail\Notification\SenderManagerInterface;

class MockSenderManager implements SenderManagerInterface
{

    protected $Senders = array();

    public function __construct($senders = array())
    {
        $this->Senders = $senders;
    }

    public function hasSender($type)
    {
        return array_key_exists($type, $this->Senders);
    }

    public function getSender($type, $options = array())
    {
        if (array_key_exists($type, $this->Senders)) {
            return $this->Senders[$type];
        }
    }
}
