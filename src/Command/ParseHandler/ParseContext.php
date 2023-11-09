<?php
declare(strict_types=1);

namespace App\Command\ParseHandler;

class ParseContext
{
    public function __construct(
        public readonly string $query,
        public array $context = []
    ) {
    }

}
