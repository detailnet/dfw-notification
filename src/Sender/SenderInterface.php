<?php

namespace Detail\Notification\Sender;

use Detail\Notification\CallInterface;

interface SenderInterface
{
    /**
     * @param array $payload
     * @param array $params
     * @return CallInterface
     */
    public function send(array $payload, array $params = []);
}
