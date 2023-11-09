<?php
declare(strict_types=1);

namespace App\Model\Table;

use Eggheads\CakephpClickHouse\AbstractClickHouseTable;

class WbProductsClickHouseTable extends AbstractClickHouseTable implements WbProductsTableInterface
{
    public const TABLE = 'wbProducts';
    public const WRITER_CONFIG = 'default';
    public const PAGE_SIZE = 1000;

    public function save(array $products): void
    {
        $transaction = $this->createTransaction();

        foreach ($products as $product) {
            $transaction->append([
                'name' => $product->getName(),
                'brand' => $product->getBrand(),
                'position' => $product->getPosition(),
                'query' => $product->getQuery(),
            ]);

            if ($transaction->count() > self::PAGE_SIZE) {
                $transaction->commit();

                $transaction = $this->createTransaction();
            }
        }

        if ($transaction->hasData()) {
            $transaction->commit();
        } else {
            $transaction->rollback();
        }
    }
}
