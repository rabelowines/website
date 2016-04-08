<?php

namespace RabeloWinesTests\Controller;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use MailingList\Exception;
use MailingList\Exception\AlreadySubscribed;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    public function setUp()
    {
        $this->setApplicationConfig(
            include 'config/application.config.php'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/');
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('RabeloWines');
        $this->assertControllerName('RabeloWines\controller\index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }

    public function testSignupSuccess()
    {
        $mc = $this->getMockBuilder('MailingList\Service\MailingListInterface')
                   ->disableOriginalConstructor()
                   ->getMock();

        $mc->expects($this->once())
           ->method('subscribe')
           ->with('noreply@rabelowines.com')
           ->willReturn(true);

        $fm = $this->getMockBuilder('\Zend\Mvc\Controller\Plugin\FlashMessenger')
                   ->disableOriginalConstructor()
                   ->getMock();

        $fm->expects($this->once())
           ->method('addMessage')
           ->with('Thank you for subscribing to our mailing list')
           ->will($this->returnSelf());
        $fm->expects($this->once())
           ->method('setNamespace')
           ->with('success')
           ->will($this->returnSelf());

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('MailingList\Service\MailingListInterface', $mc);
        $serviceManager->get('ControllerPluginManager')->setService('flashMessenger', $fm);

        $this->dispatch('/', 'POST', array('email' => 'noreply@rabelowines.com', 'listid' => 'abc123'));
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/');

        $this->assertModuleName('RabeloWines');
        $this->assertControllerName('RabeloWines\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }

    public function testSignupFailed()
    {
        $mc = $this->getMockBuilder('MailingList\Service\MailingListInterface')
                   ->disableOriginalConstructor()
                   ->getMock();

        $mc->expects($this->never())
           ->method('subscribe');

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('MailingList\Service\MailingListInterface', $mc);

        $this->dispatch('/', 'POST', array());
        $this->assertResponseStatusCode(200);

        $this->assertModuleName('RabeloWines');
        $this->assertControllerName('RabeloWines\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }

    public function testSignupAlreadySubscribed()
    {
        $mc = $this->getMockBuilder('MailingList\Service\MailingListInterface')
                   ->disableOriginalConstructor()
                   ->getMock();

        $mc->expects($this->once())
           ->method('subscribe')
           ->with('noreply@rabelowines.com')
           ->will($this->throwException(new AlreadySubscribed));

        $fm = $this->getMockBuilder('\Zend\Mvc\Controller\Plugin\FlashMessenger')
                   ->disableOriginalConstructor()
                   ->getMock();

        $fm->expects($this->once())
           ->method('addMessage')
           ->with('You are already subscribed to our mailing list')
           ->will($this->returnSelf());
        $fm->expects($this->once())
           ->method('setNamespace')
           ->with('info')
           ->will($this->returnSelf());

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('MailingList\Service\MailingListInterface', $mc);
        $serviceManager->get('ControllerPluginManager')->setService('flashMessenger', $fm);

        $this->dispatch('/', 'POST', array('email' => 'noreply@rabelowines.com', 'listid' => 'abc123'));
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/');

        $this->assertModuleName('RabeloWines');
        $this->assertControllerName('RabeloWines\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }

    public function testSignupError()
    {
        $log = $this->getMockBuilder('Zend\Log\LoggerInterface')
                    ->disableOriginalConstructor()
                    ->getMock();

        $log->expects($this->once())
            ->method('err');

        $ml = $this->getMockBuilder('MailingList\Service\MailingListInterface')
                   ->disableOriginalConstructor()
                   ->getMock();

        $ml->expects($this->once())
           ->method('subscribe')
           ->with('noreply@rabelowines.com')
           ->will($this->throwException(new Exception));

        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);
        $serviceManager->setService('Log\App', $log);
        $serviceManager->setService('MailingList\Service\MailingListInterface', $ml);

        $this->dispatch('/', 'POST', array('email' => 'noreply@rabelowines.com', 'listid' => 'abc123'));
        $this->assertResponseStatusCode(302);
        $this->assertRedirect();
        $this->assertRedirectTo('/');

        $this->assertModuleName('RabeloWines');
        $this->assertControllerName('RabeloWines\Controller\Index');
        $this->assertControllerClass('IndexController');
        $this->assertMatchedRouteName('home');
    }
}