<?php

namespace Detail\Notification;

trait NotifierAwareTrait
{
    /**
     * @var NotifierInterface|null
     */
    protected $notifier;

    /**
     * @return NotifierInterface|null
     */
    public function getNotifier()
    {
        return $this->notifier;
    }

    /**
     * @param NotifierInterface $notifier
     */
    public function setNotifier(NotifierInterface $notifier)
    {
        $this->notifier = $notifier;
    }
}
