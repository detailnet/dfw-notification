<?php

namespace Detail\Notification;

interface NotifierInterface
{
    /**
     * @param string $type
     * @param array $payload
     * @param array $params
     * @return NotificationInterface
     */
    public function createNotification($type, array $payload, array $params = []);

    /**
     * @param NotificationInterface $notification
     * @return CallInterface
     */
    public function sendNotification(NotificationInterface $notification);
}
