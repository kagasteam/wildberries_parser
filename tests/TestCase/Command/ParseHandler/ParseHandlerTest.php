<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command\ParseHandler;

use App\Command\ParseHandler\ParseContext;
use App\Command\ParseHandler\ParseHandler;
use App\Services\WbParser\ProductDTO;
use App\Services\WbParser\Repository\WbProductsRepositoryInterface;
use Cake\TestSuite\TestCase;

class ParseHandlerTest extends TestCase
{
    public function testItSaveMethodIsCall()
    {
        $productDTO = new ProductDTO('name', 'brand', 1, 'query');
        $arrayDTOs = [0 => $productDTO];
        $contextDTO = new ParseContext('query', $arrayDTOs);
        $repository = $this->createMock(WbProductsRepositoryInterface::class);

        $repository->expects(self::once())
            ->method('save');

        $parseHandler = new ParseHandler($repository);
        $parseHandler->handle($contextDTO);
    }

    public function testItThrowExceptionIfArrayOfDTOsIsEmpty()
    {
        $contextDTO = new ParseContext('query', []);
        $repository = $this->createMock(WbProductsRepositoryInterface::class);

        $repository->expects(\PHPUnit\Framework\never())
            ->method('save');

        $this->expectException(\LogicException::class);

        $parseHandler = new ParseHandler($repository);
        $parseHandler->handle($contextDTO);
    }

    public function testItThrowExceptionIfArrayNotContainsDTOs()
    {
        $contextDTO = new ParseContext('query', [0 => 'not DTO']);
        $repository = $this->createMock(WbProductsRepositoryInterface::class);

        $repository->expects(\PHPUnit\Framework\never())
            ->method('save');

        $this->expectException(\LogicException::class);

        $parseHandler = new ParseHandler($repository);
        $parseHandler->handle($contextDTO);
    }
}
