<?php
declare(strict_types=1);

namespace App\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;

class DropClickHouseTableCommand extends ClickhouseCommand
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
                DROP TABLE IF EXISTS {table_name}
            SQL,
            ['table_name' => self::TABLE_NAME]
        );

        $io->out('Таблица ' . self::TABLE_NAME . ' удалена');

        return self::CODE_SUCCESS;
    }
}
