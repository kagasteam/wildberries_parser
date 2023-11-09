<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command\ParseHandler\Middlewares;

use App\Command\ParseHandler\Middlewares\SendRequestsMiddleware;
use App\Command\ParseHandler\ParseContext;
use App\Command\ParseHandler\ParseRequestFactory;
use App\Command\ParseHandler\Pipeline;
use App\Test\TestCase\Command\ParseHandler\Middlewares\Mocks\ProductsParseResponseMock;
use Cake\TestSuite\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;

class SendRequestsMiddlewareTest extends TestCase
{
    /**
     * @throws ClientExceptionInterface
     */
    public function testItResponseContainsProductsArray()
    {
        $query = 'query';
        $countRequests = 10;
        $countProductsOnPage = 10;
        $requestsArray = [];
        for ($request = 1; $request <= $countRequests; $request++) {
            $req = ParseRequestFactory::returnParseRequest($query, $request);
            $requestsArray[] = $req;
        }
        $contextDTO = new ParseContext($query, $requestsArray);
        $pipeline = $this->getMockBuilder(Pipeline::class)
            ->disableOriginalConstructor()
            ->getMock();
        $client = $this->createMock(ClientInterface::class);
        $client->method('sendRequest')
            ->willReturnCallback(function () use ($query, $countProductsOnPage) {
                return ProductsParseResponseMock::createResponse($query, $countProductsOnPage);
            });

        $this->assertIsArray($contextDTO->context);
        $this->assertNotEmpty($contextDTO->context);
        $this->assertCount(count($requestsArray), $contextDTO->context);
        $this->assertInstanceOf(RequestInterface::class, $contextDTO->context[array_rand($contextDTO->context)]);

        $sendRequestsMiddleware = new SendRequestsMiddleware($client);
        $sendRequestsMiddleware->handle($contextDTO, fn($contextDTO) => $pipeline->handle($contextDTO));

        $this->assertIsArray($contextDTO->context);
        $this->assertNotEmpty($contextDTO->context);

        $this->assertCount($countRequests * $countProductsOnPage, $contextDTO->context);
        $this->assertArrayHasKey('name', $contextDTO->context[array_rand($contextDTO->context)]);
        $this->assertArrayHasKey('brand', $contextDTO->context[array_rand($contextDTO->context)]);
        $this->assertArrayHasKey('position', $contextDTO->context[array_rand($contextDTO->context)]);
    }
}
