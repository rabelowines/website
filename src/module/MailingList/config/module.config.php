<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'MailingList\Service\MailingListInterface' => 'MailingList\Factory\MailchimpServiceFactory',
        ),
        'abstract_factories' => array(
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
    ),

    'log' => array(
        'Log\App' => array(
            'writers' => array(
                array(
                    'name' => 'stream',
                    'priority' => 1000,
                    'options' => array(
                        'stream' => 'php://stderr',
                    ),
                ),
            ),
        ),
    ),

    'mailchimp' => array(
        'api_key' => getenv('MAILCHIMP_API_KEY'),
        'list_id' => getenv('MAILCHIMP_LIST_ID'),
    ),
);
