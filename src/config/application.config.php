<?php

return array(
    'modules' => array(
        'RabeloWines',
        'MailingList',
    ),

    'module_listener_options' => array(
        'module_paths' => array(
            './module',
        ),

        'config_glob_paths' => array(
            'config/autoload/{,*.}{global,local}.php',
        ),

        'config_cache_enabled' => true,
        'config_cache_key' => 'app_config',

        'module_map_cache_enabled' => true,
        'module_map_cache_key' => 'module_map',

        'cache_dir' => 'data/cache/',
    ),
);
