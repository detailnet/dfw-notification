<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 9/7/15
 * Time: 8:28 AM
 */


namespace tests;

use Detail\Notification\Call;
use Detail\Notification\Sender\WebhookSender;
use GuzzleHttp\Client as HttpClient;

include_once('bootstrap.php');

class WebhookSenderTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var $webhookSender WebhookSender
     */
    private $webhookSender;

    /**
     * @var $httpClient HttpClient
     */
    private $httpClient;

    /**
     * set up httpClient and webhookSender
     */
    public function setUp(){

        $this->httpClient = new HttpClient();

        $this->webhookSender = new WebhookSender($this->httpClient);
    }

    /**
     *
     */
    public function tearDown(){ }

    /**
     * @covers WebhookSender::getHttpClient
     */
    public function testGetHttpClient()
    {
        $this->webhookSender->setHttpClient($this->httpClient);
        $this->assertEquals($this->httpClient, $this->webhookSender->getHttpClient());
    }

    /**
     * @covers WebhookSender::setHttpClient
     */
    public function testSetHttpClient()
    {
        $this->webhookSender->setHttpClient($this->httpClient);
        $this->assertEquals($this->httpClient, $this->webhookSender->getHttpClient());
    }

    /**
     * sending a post to http://httpbin.org/post
     * @covers WebhookSender::send
     * @covers WebhookSender::encodePayload
     */
    public function testSend()
    {

        /** @var Call $call */
        $call = $this->webhookSender->send(array('test_value' => 99), array('url' => 'http://httpbin.org/post'));
        $now = new \DateTime();
        $this->assertEquals($call->getSentOn()->format('Y-m-d'), $now->format('Y-m-d'));
        $this->assertNull($call->getError());

    }

    /**
     * @covers WebhookSender::send
     * @covers WebhookSender::encodePayload
     * @expectedException \Exception
     */
    public function testSendThrowException(){
        //cURL error 6: Could not resolve host: xxx
        try{
            $this->webhookSender->send(array('test_value' => 99), array('url' => 'xxx'));
        }catch (\Exception $e){
            $this->assertEquals($e->getMessage(), 'cURL error 6: Could not resolve host: xxx');
        }

        //cURL error 3: <url> malformed
        try{
            $this->webhookSender->send(array('test_value' => 99), array('url' => ''));
        }catch (\Exception $e){
            $this->assertEquals($e->getMessage(), 'cURL error 3: <url> malformed');
        }

        $this->webhookSender->send(array('test_value' => 0), array('url' => '//'));
    }
}
 