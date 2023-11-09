<?php
declare(strict_types=1);

namespace App\Command\ParseHandler\Middlewares;

use App\Command\ParseHandler\ParseContext;
use App\Command\ParseHandler\ParseRequestFactory;
use App\Command\ParseHandler\ParseResult;

class RequestBuilderMiddleware implements Middleware
{
    private const COUNT_PAGE = 10;

    public function handle(ParseContext $context, callable $next): ParseResult
    {
        if ($context->query !== '') {
            $arrayOfRequests = [];
            for ($page = 1; $page <= self::COUNT_PAGE; $page++) {
                $request = ParseRequestFactory::returnParseRequest($context->query, $page);
                $arrayOfRequests[] = $request;
            }

            $context->context = $arrayOfRequests;

            return $next($context);
        } else {
            throw new \LogicException();
        }
    }
}
