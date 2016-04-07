<?php

namespace DetailTest\Notification;

use Detail\Notification\Sender\WebhookSender;
use Http\Client\Curl\Client;
use Http\Message\MessageFactory\GuzzleMessageFactory;
use Http\Message\StreamFactory\GuzzleStreamFactory;
use PHPUnit_Framework_TestCase as TestCase;

class WebhookSenderTest extends TestCase
{

    /**
     * @var WebhookSender
     */
    protected $sender;

    protected function setUp()
    {
        /* Tried using the Mock Client
         * http://docs.php-http.org/en/latest/clients/mock-client.html
         * 
         * Unfortunately it seems to have some dependencies to puli
         * which makes it way too cumbersome to deal with.
         */
        $client = new Client(new GuzzleMessageFactory(), new GuzzleStreamFactory());
        $this->sender = new WebhookSender($client);
    }

    public function testClientInstance()
    {
        $sender = $this->sender;
        $this->assertInstanceOf('Http\Client\HttpClient', $sender->getHttpClient());
    }
    
    public function testSend()
    {
        $sender = $this->sender;
        $params = array('url' => 'http://example.com');
        $payload = array('testload' => 1);
        $call = $sender->send($payload, $params);
        $this->assertInstanceOf('Detail\Notification\Call', $call);
    }

}
