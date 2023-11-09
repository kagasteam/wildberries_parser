<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command\ParseHandler\Middlewares\Mocks;

use Cake\Http\Response;
use Psr\Http\Message\ResponseInterface;

class ProductsParseResponseMock
{
    public static function createResponse(string $query, int $countProducts = 10): ResponseInterface
    {
        $products = [];
        for ($i = 0; $i < $countProducts; $i++) {
            $products[] = [
                'name' => 'Product ' . $i,
                'brand' => 'Brand ' . $i,
                'position' => 'Position ' . $i,
            ];
        }

        $data = [
            'data' => [
                'products' => $products,
            ],
        ];

        return self::prepareResponse(json_encode($data));
    }

    public static function prepareResponse($data): ResponseInterface
    {
        $response = new Response();
        $response->getBody()->write($data);
        $response->getBody()->rewind();
        $response->withStatus(200);

        return $response;
    }
}
