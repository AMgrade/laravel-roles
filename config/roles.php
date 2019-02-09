<?php

declare(strict_types = 1);


return [
    'tables' => [
        'roles' => 'roles',
        'permissions' => 'permissions',
        'users' => 'users',

        // Pivot tables.
        'role_user' => 'role_user',
        'permission_role' => 'permission_role',
        'permission_user' => 'permission_user',
    ],

    'models' => [
        'user' => 'App\User',
    ],
];
