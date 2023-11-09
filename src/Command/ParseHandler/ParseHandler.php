<?php
declare(strict_types=1);

namespace App\Command\ParseHandler;

use App\Services\WbParser\ProductDTO;
use App\Services\WbParser\Repository\WbProductsRepositoryInterface;

class ParseHandler implements ParseHandlerInterface
{
    public function __construct(private WbProductsRepositoryInterface $repository)
    {
    }

    public function handle(ParseContext $context): ParseResult
    {
        if (!empty($context->context)) {
            if ($context->context[0] instanceof ProductDTO) {
                $this->repository->save($context->context);

                return new ParseResult();
            } else {
                throw new \LogicException();
            }
        } else {
            throw new \LogicException();
        }
    }
}
