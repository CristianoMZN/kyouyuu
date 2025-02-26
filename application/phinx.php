<?php

return [
    'paths' => [
        'migrations' => [
            'tracker_migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations/tracker',
            'users_migrations' => '%%PHINX_CONFIG_DIR%%/db/migrations/users'
        ],
        'seeds' => [
            'tracker_seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds/tracker',
            'users_seeds' => '%%PHINX_CONFIG_DIR%%/db/seeds/users'
        ]
    ],
    'environments' => [
        'default_migration_table' => 'phinxlog',
        'default_environment' => 'tracker_development',
        
        'tracker_development' => [
            'adapter' => 'sqlite',
            'name' => __DIR__ . '/app/database/tracker',
            'suffix' => '.db',
            'charset' => 'utf8'
        ],
        'production' => [
            'adapter' => 'mysql',
            'host' => 'localhost',
            'name' => 'production_db',
            'user' => 'root',
            'pass' => '',
            'port' => '3306',
            'charset' => 'utf8',
        ]
    ],
    'version_order' => 'creation'
];