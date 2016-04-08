<?php

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'RabeloWines\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),

    'controllers' => array(
        'factories' => array(
            'RabeloWines\Controller\Index' => 'RabeloWines\Factory\IndexControllerFactory',
        ),
    ),

    'view_manager' => array(
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    'service_manager' => array(
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
);
