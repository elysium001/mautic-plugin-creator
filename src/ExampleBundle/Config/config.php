<?php

// plugins/ExampleBundle/Config/config.php

return [
    'name'        => 'ExampleBundle',
    'description' => 'Add here description of plugin',
    'author'      => 'Avinash Dalvi', // Change this to  plugin author 
    'version'     => '1.0.0', // Change this version to your appropriate version
    'routes'      => [
        'main' => [
            'plugin_example_sync_fetch_view' => [
                'path'       => '/hello_world_link',
                'controller' => 'MauticPlugin\ExampleBundle\Controller\DefaultController::fetchViewAction',
            ],
            'plugin_example_sync_do_fetch' => [
                'path'       => '/hello_world-fetch',
                'controller' => 'MauticPlugin\ExampleBundle\Controller\DefaultController::fetchDogsAction',
                'methods'    => ['POST'],
            ],
        ],
    ],
    'menu' => [
        'main' => [
            'priority' => 25,
            'items'    => [
                'hello_world_link' => [
                    'id'        => 'menu_hello_world_parent',
                    'iconClass' => 'fa_check-square',
                    'children'  => [
                        'hello_world_sync_view' => [
                            'route'    => 'plugin_example_sync_fetch_view',
                            'label'    => 'Hi! :)',
                        ],
                    ],
                ],
            ],
        ],
    ],
];
