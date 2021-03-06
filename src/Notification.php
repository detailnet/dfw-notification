<?php

namespace Detail\Notification;

class Notification implements
    NotificationInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $payload;

    /**
     * @var array
     */
    protected $params;

    /**
     * @param string $type
     * @param array $payload
     * @param array $params
     */
    public function __construct($type, array $payload, array $params = [])
    {
        $this->type = $type;
        $this->payload = $payload;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return array
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }
}
