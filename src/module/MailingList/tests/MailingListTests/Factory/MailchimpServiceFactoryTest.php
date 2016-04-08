<?php

namespace MailingListTests\Factory;

use MailingList\Factory\MailchimpServiceFactory;
use PHPUnit_Framework_TestCase;

class MailchimpServiceFactoryTest extends PHPUnit_Framework_TestCase
{
    public function testMailchimpCreation()
    {
        $sl = $this->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface')
                   ->disableOriginalConstructor()
                   ->getMock();

        $sl->expects($this->any())
           ->method('get')
           ->with('Config')
           ->will($this->returnValue(array('mailchimp' => array('api_key' => 'abc', 'list_id' => 'abc123'))));

        $f = new MailchimpServiceFactory();
        $s = $f->createService($sl);

        $this->assertInstanceOf('MailingList\Service\Mailchimp', $s);
        $this->assertInstanceOf('MailingList\Service\MailingListInterface', $s);
    }

    /**
     * @expectedException MailingList\Exception
     * @expectedExceptionMessage You must provide a MailChimp API key
     */
    public function testMailchimpCreationNoConfig()
    {
        $sl = $this->getMockBuilder('Zend\ServiceManager\ServiceLocatorInterface')
                   ->disableOriginalConstructor()
                   ->getMock();

        $f = new MailchimpServiceFactory();
        $s = $f->createService($sl);
    }
}