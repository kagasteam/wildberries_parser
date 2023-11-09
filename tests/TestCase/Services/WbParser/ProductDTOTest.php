<?php
declare(strict_types=1);

namespace App\Test\TestCase\Services\WbParser;

use App\Services\WbParser\ProductDTO;
use Cake\TestSuite\TestCase;

class ProductDTOTest extends TestCase
{
    public function testGetters(): void
    {
        $testName = 'name';
        $testBrand = 'brand';
        $testPosition = 1;
        $testQuery = 'query';

        $product = new ProductDTO(
            $testName,
            $testBrand,
            $testPosition,
            $testQuery
        );

        $this->assertEquals($testName, $product->getName());
        $this->assertEquals($testBrand, $product->getBrand());
        $this->assertEquals($testPosition, $product->getPosition());
        $this->assertEquals($testQuery, $product->getQuery());
    }
}
