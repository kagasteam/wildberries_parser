<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command\ParseHandler\Middlewares;

use App\Command\ParseHandler\Middlewares\RequestBuilderMiddleware;
use App\Command\ParseHandler\ParseContext;
use App\Command\ParseHandler\ParseRequestFactory;
use App\Command\ParseHandler\ParseResult;
use App\Command\ParseHandler\Pipeline;
use Cake\Http\Client\Request;
use Cake\TestSuite\TestCase;
use function PHPUnit\Framework\any;

class RequestBuilderMiddlewareTest extends TestCase
{
    public function testItContextArrayIsFilledWithTenRequestObjects()
    {
        $randomKey = random_int(0, 9);
        $contextDTO = new ParseContext('query');
        $requestBuilder = new RequestBuilderMiddleware();
        $pipeline = $this->getMockBuilder(Pipeline::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertIsArray($contextDTO->context);
        $this->assertEmpty($contextDTO->context);

        $requestBuilder->handle($contextDTO, fn($contextDTO) => $pipeline->handle($contextDTO));

        $this->assertCount(10, $contextDTO->context);
        $this->assertInstanceOf(Request::class, $contextDTO->context[$randomKey]);
    }
    public function testItThrowException()
    {
        $contextDTO = new ParseContext('');
        $requestBuilder = new RequestBuilderMiddleware();
        $pipeline = $this->getMockBuilder(Pipeline::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertEmpty($contextDTO->query);
        $this->expectException(\LogicException::class);

        $requestBuilder->handle($contextDTO, fn($contextDTO) => $pipeline->handle($contextDTO));
    }
}
