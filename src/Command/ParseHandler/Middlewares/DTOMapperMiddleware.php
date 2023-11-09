<?php
declare(strict_types=1);

namespace App\Command\ParseHandler\Middlewares;

use App\Command\ParseHandler\ParseContext;
use App\Command\ParseHandler\ParseResult;
use App\Services\WbParser\ProductDTO;

class DTOMapperMiddleware implements Middleware
{

    public function handle(ParseContext $context, callable $next): ParseResult
    {
        $batchForInsert = array_map(
            fn (array $product, int $index) => new ProductDTO(
                $product['name'] ?? '',
                $product['brand'] ?? '',
                $index + 1,
                $context->query
            ),
            $context->context,
            array_keys($context->context)
        );

        $context->context = $batchForInsert;

        return $next($context);
    }
}
