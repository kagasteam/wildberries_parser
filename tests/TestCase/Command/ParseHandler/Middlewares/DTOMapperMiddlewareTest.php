<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command\ParseHandler\Middlewares;

use App\Command\ParseHandler\Middlewares\DTOMapperMiddleware;
use App\Command\ParseHandler\ParseContext;
use App\Command\ParseHandler\Pipeline;
use App\Services\WbParser\ProductDTO;
use Cake\TestSuite\TestCase;

class DTOMapperMiddlewareTest extends TestCase
{
    public function testItArrayOfProductsIsMappedOnObjects()
    {
        $dataOfDataAfterRequest = [
            0 => [
                'name' => 'name1',
                'brand' => 'brand2',
            ],
        ];

        $contextDTO = new ParseContext('query', $dataOfDataAfterRequest);
        $dtoMapper = new DTOMapperMiddleware();
        $pipeline = $this->getMockBuilder(Pipeline::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertIsArray($contextDTO->context);
        $this->assertNotEmpty($contextDTO->context);
        $this->assertArrayHasKey('name', $contextDTO->context[array_rand($contextDTO->context)]);
        $this->assertArrayHasKey('brand', $contextDTO->context[array_rand($contextDTO->context)]);

        $dtoMapper->handle($contextDTO, fn($contextDTO) => $pipeline->handle($contextDTO));

        $this->assertIsArray($contextDTO->context);
        $this->assertInstanceOf(ProductDTO::class, $contextDTO->context[array_rand($contextDTO->context)]);
        $this->assertEquals($contextDTO->context[0]->getName(), $dataOfDataAfterRequest[0]['name']);
        $this->assertEquals($contextDTO->context[0]->getBrand(), $dataOfDataAfterRequest[0]['brand']);
        $this->assertEquals($contextDTO->context[0]->getPosition(), count($contextDTO->context));
        $this->assertEquals($contextDTO->context[0]->getQuery(), $contextDTO->query);
    }
}
