<?php

namespace Detail\Notification\Sender;

use Detail\Notification\Exception;

abstract class BaseSender implements
    SenderInterface
{
    /**
     * @var array
     */
    protected $requiredParams = [];

    /**
     * @var array
     */
    protected $defaultParams = [];

    /**
     * @return array
     */
    public function getRequiredParams()
    {
        return $this->requiredParams;
    }

    /**
     * @return array
     */
    public function getDefaultParams()
    {
        return $this->defaultParams;
    }

    /**
     * @param array $params
     * @return array
     */
    protected function validateParams(array $params = [])
    {
        $requiredParams = $this->getRequiredParams();

        foreach ($requiredParams as $requiredParam) {
            if (!array_key_exists($requiredParam, $params)) {
                throw new Exception\RuntimeException(
                    sprintf('Missing required param "%s"', $requiredParam)
                );
            }
        }

        return $params;
    }
}
