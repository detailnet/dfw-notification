<?php

namespace Detail\Notification;

interface SenderManagerInterface
{
    /**
     * @param string $type
     * @return boolean
     */
    public function hasSender($type);

    /**
     * @param string $type
     * @param array $options
     * @return Sender\SenderInterface
     */
    public function getSender($type, $options = array());
}
