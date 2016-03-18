<?php

namespace DetailTest\Notification;

use PHPUnit_Framework_TestCase as TestCase;
use HttpClient;
use Http\Client\Exception\HttpException as HttpException;

use Detail\Notification\Sender\WebhookSender;


class Test extends TestCase
{

    private $webhookSender;
    private $httpClient;

    protected function setUp(){

        $this->httpClient = new HttpClient();
        $this->webhookSender = new WebhookSender($this->httpClient);
    }

    public function testSetGetHttpClient(){

        $this->webhookSender->setHttpClient($this->httpClient);
        $this->assertEquals($this->httpClient, $this->webhookSender->getHttpClient());
    }

    public function testSend(){

        $testValues = array();
        $testValues[0] = array();
        $testValues[1] = array();
        $testValues[2] = array();

        $testValues[0]['payload'] = array('payload' => '{"name": "Sepp""}');
        $testValues[0]['params'] = array('url' => 'http://requestb.in/19t9bi21', 'method' => 'post');
        $testValues[1]['payload'] = array('payload' => 77);
        $testValues[1]['params'] = array('url' => 'http://requestb.in/19t9bi21');
        $testValues[2]['payload'] = array('payload' => '{"name": "Sepp"}');
        $testValues[2]['params'] = array('url' => 'sepp', 'method' => 'post');

        // Test 1
        $call = $this->webhookSender->send($testValues[0]['payLoad'], $testValues[0]['params']);
        $this->assertNull($call->getError(), 'Test 1 passed with no errors');

        // Test 2
        $call = $this->webhookSender->send($testValues[1]['payLoad'], $testValues[1]['params']);
        $this->assertNull($call->getError(), 'Test 2 passed with no errors');

        // Test 3
        try{
            $this->webhookSender->send($testValues[2]['payLoad'], $testValues[2]['params']);
        }
        catch (HttpException $error){
            $this->assertContains($error->getMessage(), 'error', 'Test 3 thowed an error because of an invalid URL');
        }
    }


}
