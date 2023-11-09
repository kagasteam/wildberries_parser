<?php

declare(strict_types=1);

namespace App\Command;

use Cake\Command\Command;
use ClickHouseDB\Client;

abstract class ClickhouseCommand extends Command
{
    protected const TABLE_NAME = 'wbProducts';

    /**
     * @param Client $clickHouseClient
     */
    public function __construct(protected Client $clickHouseClient)
    {
        parent::__construct();
    }
}
