<?php

namespace Detail\Notification;

interface NotifierAwareInterface
{
    /**
     * @param NotifierInterface $notifier
     */
    public function setNotifier(NotifierInterface $notifier);
}
