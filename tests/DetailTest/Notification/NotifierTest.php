<?php
/**
 * Created by PhpStorm.
 * User: sullenboom
 * Date: 27/08/15
 * Time: 20:02
 */

namespace DetailTest\Notification;

use DetailTest\Mock\Notification as MockNotification;
use DetailTest\Mock\Sender as MockSender;
use DetailTest\Mock\Senders as MockSenders;

use Detail\Notification\Notifier;

class NotifierTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var MockSenders
     */
    private $senders;

    public function setUp()
    {
        $this->senders = new MockSenders(
            ['MockSender' => new MockSender()]
        );
    }

    public function tearDown()
    {
        $this->senders = null;
    }

    public function testGetSenders()
    {
        $notifier = new Notifier($this->senders);
        $senders = $notifier->getSenders();

        $this->assertInstanceOf('DetailTest\Mock\Senders', $senders);
        $this->assertTrue($senders->hasSender('MockSender'));
        $this->assertFalse($senders->hasSender('MockSender2'));
        $this->assertInstanceOf('DetailTest\Mock\Sender', $senders->getSender('MockSender'));
    }

    public function testCreateNotification()
    {
        $payload = [
            'foo' => 'bar'
        ];
        $notifier = new Notifier($this->senders);
        $notification = $notifier->createNotification('MockSender', $payload);

        $this->assertEquals('MockSender', $notification->getType());
        $this->assertEquals($payload, $notification->getPayload());
        $this->assertEmpty($notification->getParams());

        return $notifier;
    }

    /**
     * @depends testCreateNotification
     *
     * @param Notifier $notifier
     */
    public function testSendNotification(Notifier $notifier)
    {
        $notification = new MockNotification(
            'MockSender',
            ['foo' => 'bar'],
            ['param' => 'value']
        );
        $call = $notifier->sendNotification($notification);

        $this->assertTrue($call->isSuccess());
        $this->assertFalse($call->isError());
        $this->assertNull($call->getError());
        $this->assertNull($call->getErrorMessage());
        $this->assertNotEmpty($call->getSentOn());
    }

    /**
     * @depends testCreateNotification
     *
     * @param Notifier $notifier
     * @expectedException Detail\Notification\Exception\RuntimeException
     */
    public function testSendNotificationWithWrongSender(Notifier $notifier)
    {
        $notification = new MockNotification(
            'MockSender2',
            ['foo' => 'bar'],
            ['param' => 'value']
        );
        $notifier->sendNotification($notification);
    }
}