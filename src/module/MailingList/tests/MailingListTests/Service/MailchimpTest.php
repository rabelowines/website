<?php

namespace MailingListTests\Service;

use MailingList\Service\Mailchimp;
use PHPUnit_Framework_TestCase;

class MailchimpTest extends PHPUnit_Framework_TestCase
{
    public function testSubscribeSuccess()
    {
        $server = $this->getMockBuilder('Zend\Stdlib\ParametersInterface')
                       ->disableOriginalConstructor()
                       ->getMock();

        $server->expects($this->at(0))
               ->method('get')
               ->with('REMOTE_ADDR')
               ->will($this->returnValue('1.2.3.4'));

        $server->expects($this->at(1))
               ->method('get')
               ->with('REQUEST_TIME')
               ->will($this->returnValue('123456'));

        $request = $this->getMockBuilder('Zend\Http\PhpEnvironment\Request')
                        ->disableOriginalConstructor()
                        ->getMock();

        $request->expects($this->any())
                ->method('getServer')
                ->will($this->returnValue($server));

        $mc = $this->getMockBuilder('Mailchimp')
                   ->disableOriginalConstructor()
                   ->getMock();

        $mc->expects($this->once())
           ->method('call')
           ->with('lists/subscribe', [
              'id' => 'abc123',
              'email' => ['email' => 'noreply@rabelowines.com'],
              'merge_vars' => [
                'optin_ip' => '1.2.3.4',
                'optin_time' => '123456',
                'mc_location' => ['anything' => 'something'],
              ],
              'email_type' => 'html',
              'double_optin' => true,
              'update_existing' => false,
              'replace_interests' => true,
              'send_welcome' => false,
              ])
           ->will($this->returnValue(['email' => 'noreply@rabelowines.com']));

        $s = new Mailchimp($mc, 'abc123');
        $s->subscribe('noreply@rabelowines.com', $request);
    }

    /**
     * @expectedException MailingList\Exception\AlreadySubscribed
     * @expectedExceptionMessage Already subscribed
     */
    public function testAlreadySubscribed()
    {
        $server = $this->getMockBuilder('Zend\Stdlib\ParametersInterface')
                       ->disableOriginalConstructor()
                       ->getMock();

        $server->expects($this->at(0))
               ->method('get')
               ->with('REMOTE_ADDR')
               ->will($this->returnValue('1.2.3.4'));

        $server->expects($this->at(1))
               ->method('get')
               ->with('REQUEST_TIME')
               ->will($this->returnValue('123456'));

        $request = $this->getMockBuilder('Zend\Http\PhpEnvironment\Request')
                        ->disableOriginalConstructor()
                        ->getMock();

        $request->expects($this->any())
                ->method('getServer')
                ->will($this->returnValue($server));

        $mc = $this->getMockBuilder('Mailchimp')
                   ->disableOriginalConstructor()
                   ->getMock();

        $mc->expects($this->once())
           ->method('call')
           ->with('lists/subscribe')
           ->will($this->throwException(new \Mailchimp_List_AlreadySubscribed('Already subscribed')));

        $s = new Mailchimp($mc, 'abc123');
        $s->subscribe('noreply@rabelowines.com', $request);
    }

    /**
     * @expectedException MailingList\Exception
     * @expectedExceptionMessage Something went wrong
     */
    public function testError1()
    {
        $server = $this->getMockBuilder('Zend\Stdlib\ParametersInterface')
                       ->disableOriginalConstructor()
                       ->getMock();

        $server->expects($this->at(0))
               ->method('get')
               ->with('REMOTE_ADDR')
               ->will($this->returnValue('1.2.3.4'));

        $server->expects($this->at(1))
               ->method('get')
               ->with('REQUEST_TIME')
               ->will($this->returnValue('123456'));

        $request = $this->getMockBuilder('Zend\Http\PhpEnvironment\Request')
                        ->disableOriginalConstructor()
                        ->getMock();

        $request->expects($this->any())
                ->method('getServer')
                ->will($this->returnValue($server));

        $mc = $this->getMockBuilder('Mailchimp')
                   ->disableOriginalConstructor()
                   ->getMock();

        $mc->expects($this->once())
           ->method('call')
           ->with('lists/subscribe')
           ->will($this->throwException(new \Mailchimp_Error('Something went wrong')));

        $s = new Mailchimp($mc, 'abc123');
        $s->subscribe('noreply@rabelowines.com', $request);
    }

    /**
     * @expectedException MailingList\Exception
     * @expectedExceptionMessage Failed to subscribe user
     */
    public function testError2()
    {
        $server = $this->getMockBuilder('Zend\Stdlib\ParametersInterface')
                       ->disableOriginalConstructor()
                       ->getMock();

        $server->expects($this->at(0))
               ->method('get')
               ->with('REMOTE_ADDR')
               ->will($this->returnValue('1.2.3.4'));

        $server->expects($this->at(1))
               ->method('get')
               ->with('REQUEST_TIME')
               ->will($this->returnValue('123456'));

        $request = $this->getMockBuilder('Zend\Http\PhpEnvironment\Request')
                        ->disableOriginalConstructor()
                        ->getMock();

        $request->expects($this->any())
                ->method('getServer')
                ->will($this->returnValue($server));

        $mc = $this->getMockBuilder('Mailchimp')
                   ->disableOriginalConstructor()
                   ->getMock();

        $mc->expects($this->once())
           ->method('call')
           ->with('lists/subscribe')
           ->will($this->returnValue(false));

        $s = new Mailchimp($mc, 'abc123');
        $s->subscribe('noreply@rabelowines.com', $request);
    }
}