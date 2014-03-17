<?php

return array(
    'system' => array(
        'router' => array(
            'default' => 'main/index/index',
            'defaultAuth' => 'admin/auth/login',
        ),
        'auth' => array(
            'gravatar' => true
        ),
        'view' => array(
            'theme' => 'kf',
            'layout' => 'layout.phtml',
            'error404' => 'error404.phtml',
            'datagrid' => array(
                'rowsPerPage' => 10
            )
        )
    )
);
