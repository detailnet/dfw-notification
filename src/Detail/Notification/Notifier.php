<?php

namespace Detail\Notification;

class Notifier implements
    NotifierInterface
{
    /**
     * @var SenderManagerInterface
     */
    protected $senders;

    /**
     * @param SenderManagerInterface $senders
     */
    public function __construct(SenderManagerInterface $senders)
    {
        $this->setSenders($senders);
    }

    /**
     * @return SenderManagerInterface
     */
    public function getSenders()
    {
        return $this->senders;
    }

    /**
     * @param SenderManagerInterface $senders
     */
    public function setSenders(SenderManagerInterface $senders)
    {
        $this->senders = $senders;
    }

    /**
     * @param string $type
     * @param array $payload
     * @param array $params
     * @return Notification
     */
    public function createNotification($type, array $payload, array $params = array())
    {
        return new Notification($type, $payload, $params);
    }

    /**
     * @param NotificationInterface $notification
     * @return CallInterface
     */
    public function sendNotification(NotificationInterface $notification)
    {
        $senders = $this->getSenders();

        if (!$senders->hasSender($notification->getType())) {
            throw new Exception\RuntimeException(
                sprintf('No sender registered for notification type "%s"', $notification->getType())
            );
        }

        $sender = $senders->getSender($notification->getType());
        $call = $sender->send($notification->getPayload(), $notification->getParams());

        return $call;
    }
}
