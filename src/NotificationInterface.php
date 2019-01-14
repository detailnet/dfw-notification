<?php

namespace Detail\Notification;

interface NotificationInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * @return array
     */
    public function getPayload();

    /**
     * @return array
     */
    public function getParams();
}
