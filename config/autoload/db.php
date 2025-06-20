<?php

declare(strict_types=1);

use App\DB\CustomPgSQLPool;
use Hyperf\Support;

return [
    'default' => [
        'driver' => CustomPgSQLPool::class,
        'host' => Support\env('DB_HOST'),
        'database' => Support\env('DB_DATABASE'),
        'port' => Support\env('DB_PORT', 5432),
        'username' => Support\env('DB_USERNAME'),
        'password' => Support\env('DB_PASSWORD'),
        'pool' => [
            'min_connections' => 5,
            'max_connections' => 100,
            'connect_timeout' => 10.0,
            'wait_timeout' => 5.0,
            'heartbeat' => -1,
            'max_idle_time' => 60,
        ],
        'commands' => [
            'gen:model' => [
                'path' => 'app/Model',
                'force_casts' => true,
                'inheritance' => 'Model',
            ],
        ],
    ],
];
