<?php

return [
    'driver' => 'pgsql',

    'url' => env('NEON_DATABASE_URL'),

    'host' => '127.0.0.1',
    'port' => '5432',
    'database' => 'neondb',
    'username' => 'neondb_owner',
    'password' => '',

    'charset' => 'utf8',
    'prefix' => '',
    'prefix_indexes' => true,
    'search_path' => 'public',
    'sslmode' => 'require',
];