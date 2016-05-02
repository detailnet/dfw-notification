<?php
/**
 * Created by PhpStorm.
 * User: sullenboom
 * Date: 26/08/15
 * Time: 20:13
 */

namespace DetailTests\Notification\Sender;

use GuzzleHttp\Client as HttpClient;

use Detail\Notification\Sender\WebhookSender;
use Detail\Notification\Exception\RuntimeException as DetailRuntimeException;

class WebhookSenderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var HttpClient
     */
    private $_client;

    public function setUp()
    {
        $this->_client = new HttpClient();
    }

    /**
     * @param array $config
     * @return HttpClient
     * @internal param HttpClient $client
     */
    private function createClient(array $config = [])
    {
        return new HttpClient($config);
    }

    /**
     * @param HttpClient $client
     * @return WebhookSender
     */
    private function createSender(HttpClient $client = null)
    {
        if ($client === null) {
            $client = $this->createClient();
        }
        return new WebhookSender($client);
    }

    public function testCreateSender()
    {
        $sender = $this->createSender();
        $this->assertInstanceOf('Detail\Notification\Sender\WebhookSender', $sender);
    }

    public function testSetHttpClient()
    {
        $client = $this->createClient(['base_url' => 'http://new.url']);

        $sender = $this->createSender();
        $sender->setHttpClient($client);

        $this->assertInstanceOf('GuzzleHttp\Client', $client);
        $this->assertEquals('http://new.url', $client->getBaseUrl());
    }

    /**
     * @expectedException Detail\Notification\Exception\InvalidArgumentException
     */
    public function testMissingParameter()
    {
        $sender = $this->createSender();
        $sender->send(array(), array());
    }

    public function testSend()
    {
        $payload = array(WebhookSender::PARAM_URL => 'http://testing.de');
        $params = array(WebhookSender::PARAM_URL => 'http://testing.de');
        $sender = $this->createSender();
        $call = $sender->send($payload, $params);

        $this->assertInstanceOf('Detail\Notification\Call', $call);
    }

    /**
     * @dataProvider incorrectPayloads
     *
     * @param array $payload
     * @param integer $exceptedError
     */
    public function testJsonErrorHandling(array $payload, $exceptedError)
    {
        $params = array(WebhookSender::PARAM_URL => 'http://testing.de');
        $sender = $this->createSender();

        try {
            $sender->send($payload, $params);
            $this->fail();
        } catch (DetailRuntimeException $e) {
            $this->assertEquals($exceptedError, json_last_error());
        }

    }

    /**
     * @return array
     */
    public function incorrectPayloads()
    {
        /**
         * Should be implemented
            //array([], JSON_ERROR_DEPTH),
            array([], JSON_ERROR_STATE_MISMATCH),
            array([], JSON_ERROR_CTRL_CHAR),
            array([], JSON_ERROR_SYNTAX),
         */
        return array(
            array([chr(0x8A) => chr(0xA9)], JSON_ERROR_UTF8)
        );
    }
}

