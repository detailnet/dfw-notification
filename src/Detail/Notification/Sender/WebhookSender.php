<?php

namespace Detail\Notification\Sender;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\BadResponseException as HttpException;

use Detail\Notification\Call;
use Detail\Notification\Exception;

class WebhookSender extends BaseSender
{
    const PARAM_URL    = 'url';
    const PARAM_METHOD = 'method';

    /**
     * @var array
     */
    protected $requiredParams = array(
        self::PARAM_URL,
        self::PARAM_METHOD
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

        /** @todo Validate URL? */
        $url = $getParam(self::PARAM_URL);
        $http_method = $getParam(self::PARAM_METHOD);
        $httpClient = $this->getHttpClient();
        $error = null;

        try {
            if (empty($payload)) {
                // assume that were not sending FORM data.
                // use HTTP method defined in required parameter
                // 'method'
                // $http_method maps to the configured method type
                // retreived from required params
                $httpClient->$http_method($url);
            } else {
                // if there is a payload to be sent,
                // choose HTTP POST command to send FORM data as JSON type
                $httpClient->post(
                    $url,
                    array(
                        'body' => $this->encodePayload($payload),
                        'headers' => array(
                            'Content-Type' => 'application/json',
                        ),
                    )
                );
            }

        } catch (HttpException $e) {
            $error = $e;
        }

        $call = new Call($error);

        return $call;
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
