<?php
declare(strict_types=1);

namespace App\Command\ParseHandler\Middlewares;

use App\Command\ParseHandler\ParseContext;
use App\Command\ParseHandler\ParseResult;
use Psr\Http\Client\ClientInterface;

class SendRequestsMiddleware implements Middleware
{
    public function __construct(
        private ClientInterface $client
    )
    {
    }

    /**
     * @param ParseContext $context
     * @param callable $next
     * @return ParseResult
     * @throws \Psr\Http\Client\ClientExceptionInterface
     */
    public function handle(ParseContext $context, callable $next): ParseResult
    {
        $countRequests = count($context->context);
        $data = [];
        for ($request = 0; $request < $countRequests; $request++) {
            $result = $this->client->sendRequest($context->context[$request])->getBody()->getContents();
            $jsonData = json_decode($result, true);
            $products = $jsonData['data']['products'];
            $data = array_merge($data, $products);
        }

        $context->context = $data;

        return $next($context);
    }
}
