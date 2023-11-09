<?php

declare(strict_types=1);

namespace App\Model\Table;

use App\Service\WbProducts\DTO\Product;
use Eggheads\CakephpClickHouse\ClickHouseTableInterface;

interface WbProductsTableInterface extends ClickHouseTableInterface
{
    /**
     * @param array<Product> $products
     * @return void
     */
    public function save(array $products): void;
}
