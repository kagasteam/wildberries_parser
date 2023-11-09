<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class InitClickHouseTableCommand extends ClickhouseCommand
{
    /**
     * @param \Cake\Console\Arguments $args
     * @param \Cake\Console\ConsoleIo $io
     * @return int|void|null
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $this->clickHouseClient->write(
            <<<'SQL'
                CREATE TABLE IF NOT EXISTS {table_name} (
                    name String,
                    position UInt32,
                    brand String,
                    query String
                )
                ENGINE = ReplacingMergeTree()
                PARTITION BY sipHash64(query)
                ORDER BY (query, position);
            SQL,
            ['table_name' => self::TABLE_NAME]
        );

        $io->out('Таблица ' . self::TABLE_NAME . ' создана');

        return self::CODE_SUCCESS;
    }
}
