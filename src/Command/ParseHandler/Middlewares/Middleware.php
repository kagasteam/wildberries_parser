<?php

namespace App\Command\ParseHandler\Middlewares;

use App\Command\ParseHandler\ParseContext;
use App\Command\ParseHandler\ParseResult;

interface Middleware
{
    /**
     * @param ParseContext $context
     * @param callable(ParseContext): ParseResult $next
     * @return ParseResult
     */
    public function handle(ParseContext $context, callable $next): ParseResult;
}
