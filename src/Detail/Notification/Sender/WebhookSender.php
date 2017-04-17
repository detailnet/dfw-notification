<?php

namespace Detail\Notification\Sender;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException as HttpException;

use Detail\Notification\Call;
use Detail\Notification\Exception;

class WebhookSender extends BaseSender
{
    const PARAM_URL = 'url';

    /**
     * @var array
     */
    protected $requiredParams = array(
        self::PARAM_URL,
    );

    /**
     * @var array
     */
    protected $defaultParams = array(
        self::PARAM_METHOD => 'POST'
    );

    /**
     * @var array
     */
    protected $allowedMethods = array(
        'post'
    );

    /**
     * @var HttpClient
     */
    protected $httpClient;

    /**
     * @param HttpClient $httpClient
     */
    public function __construct(HttpClient $httpClient)
    {
        $this->setHttpClient($httpClient);
    }

    /**
     * @return HttpClient
     */
    public function getHttpClient()
    {
        return $this->httpClient;
    }

    /**
     * @param HttpClient $httpClient
     */
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param array $payload
     * @param array $params
     * @return Call
     */
    public function send(array $payload, array $params = array())
    {
        $params = $this->validateParams($params);

        $getParam = function ($key, $default = null) use ($params) {
            return array_key_exists($key, $params) ? $params[$key] : $default;
        };

        $httpClient = $this->getHttpClient();
        $error = null;

        try {
            $url = $this->verifyUrl($getParam(self::PARAM_URL));
            $method = $this->verifyMethod($getParam(self::PARAM_METHOD, 'post'));

            $httpClient->$method(
                $url,
                array(
                    'body' => $this->encodePayload($payload),
                    'headers' => array(
                        'Content-Type' => 'application/json',
                    ),
                )
            );
        } catch (HttpException $e) {
            $error = $e;


        } catch (Exception\InvalidArgumentException $e) {
            $error = $e;
        }

        $call = new Call($error);

        return $call;
    }

    /**
     * @param string $url
     * @return string
     * @throws Exception\InvalidArgumentException
     */
    protected function verifyUrl($url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            $message = 'Url not allowed or wrong: ' . $url;
            throw new Exception\InvalidArgumentException($message);
        }
        return $url;
    }

    /**
     * @param string $method
     * @return string
     * @throws Exception\InvalidArgumentException
     */
    protected function verifyMethod($method)
    {
        $method = strtolower($method);
        if (!in_array($method, $this->allowedMethods)) {
            $message = 'Sender method not allowed, please use: ' . implode(', ', $this->allowedMethods);
            throw new Exception\InvalidArgumentException($message);
        }
        return $method;
    }

    /**
     * @param array $payload
     * @return string
     */
    protected function encodePayload(array $payload)
    {
        $body = json_encode($payload);
        $error = json_last_error();

        if ($error !== JSON_ERROR_NONE) {
            switch (json_last_error()) {
                case JSON_ERROR_DEPTH:
                    $message = 'Maximum stack depth exceeded';
                    break;
                case JSON_ERROR_STATE_MISMATCH:
                    $message = 'Underflow or the modes mismatch';
                    break;
                case JSON_ERROR_CTRL_CHAR:
                    $message = 'Unexpected control character found';
                    break;
                case JSON_ERROR_SYNTAX:
                    $message = 'Syntax error, malformed JSON';
                    break;
                case JSON_ERROR_UTF8:
                    $message = 'Malformed UTF-8 characters, possibly incorrectly encoded';
                    break;
                default:
                    $message = 'Unknown error';
                    break;
            }

            throw new Exception\RuntimeException(
                'Failed to encode payload to JSON; ' . $message
            );
        }

        return $body;
    }
}
