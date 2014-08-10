<?php

return [
    'system' => [
        'acl' => [
            'enabled' => true
        ],
        'router' => [
            'error' => [
                '404' => ['controller' => 'Admin\Controller\Index', 'action' => 'error404'], // Module, Controller or Action not found
                '304' => ['controller' => 'Admin\Controller\Index', 'action' => 'error404'], // File not found
                '400' => ['controller' => 'Admin\Controller\Index', 'action' => 'errorDefault'], // Template not found
                '7' => ['controller' => 'Admin\Controller\Index', 'action' => 'errorDefault'], // Database doesn't exists
                '333' => ['controller' => 'Admin\Controller\Index', 'action' => 'errorDefault'], // Generic error
            ],
            'default' => '/admin/index/index',
            'defaultAuth' => '/admin/auth/login',
        ],
        'auth' => [
            'gravatar' => true
        ],
        'view' => [
            'theme' => 'kf',
            'layout' => 'layout.phtml',
            'error404' => 'error404.phtml',
            'errorDefault' => 'errorDefault.phtml',
            'datagrid' => [
                'rowsPerPage' => 15
            ]
        ]
    ]
];
