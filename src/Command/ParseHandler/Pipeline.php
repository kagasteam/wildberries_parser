<?php
declare(strict_types=1);

namespace App\Command\ParseHandler;

use App\Command\ParseHandler\Middlewares\Middleware;
class Pipeline
{
    /**
     * @param list<Middleware> $middlewares
     */
    public function __construct(
        private ParseHandlerInterface $handler,
        public array $middlewares = []
    ) {
    }

    public function handle(ParseContext $context): ParseResult
    {
        $middleware = array_shift($this->middlewares);

        if ($middleware !== null) {
            return $middleware->handle($context, [$this, 'handle']);
        }

        return $this->handler->handle($context);
    }
}
