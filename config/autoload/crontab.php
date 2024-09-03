<?php

use Hyperf\Crontab\Crontab;
use App\Task\Warmup;

return [
    'enable' => Hyperf\Support\env("ACTIVE_CRON", false),
    // Timed tasks defined by configuration
    'crontab' => [
        // (new Crontab())
        //     ->setName('Cron Name')
        //     ->setRule('30 3 * * *')
        //     ->setCallback([Warmup::class, 'class'])
        //     ->setMemo('A memo'),
				(new Crontab())
            ->setName('Municipios')
            ->setRule('47 * * * *')
            ->setCallback([Warmup::class, 'warmupMunicipios'])
            ->setMemo('Warmuo Municipios deve executar uma vez por mês.'),
    ],
];