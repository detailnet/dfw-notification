<?php
/**
 * Created by PhpStorm.
 * User: sullenboom
 * Date: 27/08/15
 * Time: 21:00
 */

namespace DetailTest\Mock;

use Detail\Notification\NotificationInterface;

class Notification implements NotificationInterface
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var array
     */
    protected $payload = array();

    /**
     * @var array
     */
    protected $params = array();

    /**
     * @param string $type
     * @param array $payload
     * @param array $params
     */
    public function __construct($type, array $payload, array $params = array())
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