<?php

namespace DetailTest\Notification;

use Detail\Notification\Sender\BaseSender;

class MockSender extends BaseSender
{
    public function __construct()
    {
    }

    public function send(array $payload, array $params = array())
    {
        $call = new Call();
        return $call;
    }
}
