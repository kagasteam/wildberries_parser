<?php
declare(strict_types=1);

namespace App\Services\WbParser\Repository;

use App\Model\Table\WbProductsTableInterface;
use App\Services\WbParser\ProductDTO;

class WbProductsRepository implements WbProductsRepositoryInterface
{
    public function __construct(
        private readonly WbProductsTableInterface $wbProductsTable
    ){
    }

    public function get(string $query, ?int $limit, ?int $offset): array
    {
        {
            $rows = $this->wbProductsTable
                ->select(
                    <<<'SQL'
                        SELECT `name`, `brand`, `position`, `query`
                        FROM wbProducts
                        WHERE query = :query
                        LIMIT :limit
                        OFFSET :offset
                    SQL,
                        [
                            'query' => $query,
                            'limit' => $limit,
                            'offset' => $offset
                        ]

                )
                ->rows();

            return array_map(
                fn (array $row) => new ProductDTO($row['name'], $row['brand'], $row['position'], $row['query']),
                $rows
            );
        }
    }

    /**
     * @param list<ProductDTO> $products
     * @return void
     */
    public function save(array $products): void
    {
        $this->wbProductsTable->save($products);
    }
}
