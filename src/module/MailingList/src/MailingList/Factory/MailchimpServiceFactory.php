<?php

namespace MailingList\Factory;

use Mailchimp_Error;
use MailingList\Service\Mailchimp;
use MailingList\Exception;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class MailchimpServiceFactory implements FactoryInterface
{
    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        try {
            return new Mailchimp(new \Mailchimp($config['mailchimp']['api_key']), $config['mailchimp']['list_id']);
        } catch (Mailchimp_Error $e) {
            throw new Exception($e->getMessage());
        }
    }
}