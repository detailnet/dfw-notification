<?php

namespace DetailTest\Mock;

use Detail\Notification\Call;
use Detail\Notification\Sender\BaseSender;

class Sender extends BaseSender
{
    /**
     * @param array $payload
     * @param array $params
     * @return Call
     */
    public function send(array $payload, array $params = array())
    {
        return new Call();
    }
}