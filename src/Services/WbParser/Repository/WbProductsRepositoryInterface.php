<?php

namespace App\Services\WbParser\Repository;

use App\Services\WbParser\ProductDTO;

interface WbProductsRepositoryInterface
{
    public function get(string $queryString, ?int $limit, int $offset): array;

    /**
     * @param list<ProductDTO> $products
     * @return void
     */
    public function save(array $products): void;
}
