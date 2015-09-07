<?php
/**
 * Created by PhpStorm.
 * User: jonas
 * Date: 9/6/15
 * Time: 5:02 PM
 */

namespace tests;


use Detail\Notification\Notification;
use Detail\Notification\Notifier;
use Detail\Notification\SenderManagerInterface;

include_once('bootstrap.php');

class NotifierTest extends \PHPUnit_Framework_TestCase {

    /** @var $notifier Notifier */
    private $notifier;

    /** @var  $sender SenderManagerInterface */
    private $senders;

    /**
     * set up notifier and mock sender
     */
    public function setUp(){

        $this->senders = $this->getMockBuilder('Detail\Notification\SenderManagerInterface')->getMock();

        $this->notifier = new Notifier($this->senders);
    }
    public function tearDown(){ }


    /**
     * @covers Notifier::getSenders
     */
    public function testGetSenders()
    {
        $sender = $this->notifier->getSenders();
        $this->assertEquals($sender, $this->senders);
    }

    /**
     * @covers Notifier::setSenders
     */
    public function testSetSenders()
    {
        $this->notifier->setSenders($this->senders);
        $this->assertEquals($this->senders, $this->notifier->getSenders());
    }


    /**
     * @covers Notifier::createNotification
     */
    public function testCreateNotification()
    {
        $notification = new Notification('test_type', array('payload' => 'test_payload'), array('param' => 'test_param'));
        $this->assertTrue($notification instanceof Notification);
    }


    /**
     * @covers Notifier::sendNotification
     * @expectedException \Exception
     * TODO mock senders and test with valid type
     */
    public function testSendNotificationNoRegisteredSender()
    {
        $notification = new Notification('test_type', array('payload' => 'test_payload'), array('param' => 'test_param'));
        $this->notifier->sendNotification($notification);
    }


}
 