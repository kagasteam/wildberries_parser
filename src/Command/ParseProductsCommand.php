<?php
declare(strict_types=1);

namespace App\Command;

use App\Command\ParseHandler\Middlewares\Middleware;
use App\Command\ParseHandler\ParseHandlerInterface;
use App\Command\ParseHandler\ParseContext;
use App\Command\ParseHandler\Pipeline;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;
class ParseProductsCommand extends ClickhouseCommand
{
    private const PARSING_PHRASE = 'query';

    /**
     * @param ParseHandlerInterface $handler
     * @param list<Middleware> $middlewares
     */
    public function __construct(
        private readonly ParseHandlerInterface $handler,
        private readonly array $middlewares
    ) {
    }

    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser->addArgument(self::PARSING_PHRASE);

        return $parser;
    }

    public function execute(Arguments $args, ConsoleIo $io)
    {
        $parsingPhrase = mb_strtolower(trim((string)$args->getArgument(self::PARSING_PHRASE)));
        if ($parsingPhrase !== '') {
            $context = new ParseContext($parsingPhrase);
            (new Pipeline($this->handler, $this->middlewares))->handle($context);

            $io->out('Запрос "' . $parsingPhrase . '" выполнен!');
        } else {
            $io->error('Введите фразу для парсинга!');
        }
    }
}
