<?php

return [
    'system' => [
        'router' => [
            'error' => [
                '404' => ['controller' => 'Admin\Controller\Index', 'action' => 'error404']
            ],
            'default' => '/main/index/index',
            'defaultAuth' => '/admin/auth/login',
        ],
        'auth' => [
            'gravatar' => true
        ],
        'view' => [
            'theme' => 'kf',
            'layout' => 'layout.phtml',
            'error404' => 'error404.phtml',
            'datagrid' => [
                'rowsPerPage' => 10
            ]
        ]
    ]
];
