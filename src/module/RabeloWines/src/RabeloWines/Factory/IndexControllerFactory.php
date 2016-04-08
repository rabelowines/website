<?php

namespace RabeloWines\Factory;

use RabeloWines\Controller\IndexController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class IndexControllerFactory implements FactoryInterface
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
        $realServiceLocator = $serviceLocator->getServiceLocator();
        $mailingListService = $realServiceLocator->get('MailingList\Service\MailingListInterface');
        $logger = $realServiceLocator->get('Log\App');

        return new IndexController($mailingListService, $logger);
    }
}