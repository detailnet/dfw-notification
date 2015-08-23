<?php

namespace DetailTest\Notification;

use DetailTest\Notification\MockNotification;
use DetailTest\Notification\MockSenderManager;
use DetailTest\Notification\MockSender;

use Detail\Notification\Notifier;
use Detail\Notification\Call;
use Detail\Notification\Exception\RuntimeException;

/** @todo: This is just a start.... There should be more than
happy flow testing here. Especially for testSendNotification */

/**
 * Test for Notifier class.
 */
class NotifierTest extends \PHPUnit_Framework_TestCase
{
    public function testGetSenders()
    {
        // create mock objects for test
        $sender = new MockSender();
        $senderManager = new MockSenderManager(
            array(
                "MockSender" => $sender
            )
        );

        $notifier = new Notifier($senderManager);

        $senderManagerInterface = $notifier->getSenders();
        $this->assertNotNull($senderManagerInterface, 'Empty Sender Manager Interface.');
    }

    public function testCreateNotification()
    {
        // create mock objects for test
        $sender = new MockSender();
        $senderManager = new MockSenderManager(
            array(
                "MockSender" => $sender
            )
        );

        $notifier = new Notifier($senderManager);

        // testing
        $notification = $notifier->createNotification(
            "MockNotification",
            array(
                "Data1" => "Foo",
                "Data2" => "Bar"
            )
        );

        $this->assertEquals("MockNotification", $notification->getType());
        $payload = $notification->getPayload();
        $this->assertArrayHasKey("Data1", $payload);
        $this->assertArrayHasKey("Data2", $payload);
        $this->assertEquals("Foo", $payload["Data1"]);
        $this->assertEquals("Bar", $payload["Data2"]);
    }

    public function testSendNotification()
    {
        // create mock objects for test
        $sender = new MockSender();
        $senderManager = new MockSenderManager(
            array(
                "MockSender" => $sender
            )
        );

        $notifier = new Notifier($senderManager);

        $notification = new MockNotification(
            "MockNotification",
            array(
                "Data1" => "Foo",
                "Data2" => "Bar"
            )
        );


        // testing
        try {
            $call = $notifier->sendNotification($notification);

            $this->assertNotNull($call);
            $this->assertNotNull($call->sentOn());

        } catch (RuntimeException $e) {
            $this->assertFalse(false, 'Runtime Exception caught while sending notification.');
        }
    }
}
