<?php

namespace Detail\Notification;

use DateTime;
use Exception;

class Call implements
    CallInterface
{
    /**
     * @var string|Exception
     */
    protected $error;

    /**
     * @var DateTime
     */
    protected $sentOn;

    /**
     * @param string|Exception $error
     * @param DateTime $sentOn
     */
    public function __construct($error = null, DateTime $sentOn = null)
    {
        if ($sentOn === null) {
            $sentOn = new DateTime();
        }

        $this->error = $error;
        $this->sentOn = $sentOn;
    }

    /**
     * @return boolean
     */
    public function isSuccess()
    {
        return !$this->isError();
    }

    /**
     * @return boolean
     */
    public function isError()
    {
        return $this->getError() !== null;
    }

    /**
     * @return string|Exception|null
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @return string|null
     */
    public function getErrorMessage()
    {
        $error = $this->getError();

        return ($error instanceof Exception) ? $error->getMessage() : $error;
    }

    /**
     * @return DateTime
     */
    public function getSentOn()
    {
        return $this->sentOn;
    }
}
