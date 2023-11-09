<?php
declare(strict_types=1);

namespace App\Test\TestCase\Command\ParseHandler;

use App\Command\ParseHandler\ParseRequestFactory;
use Cake\Http\Client\Request;
use Cake\TestSuite\TestCase;

class ParseRequestFactoryTest extends TestCase
{
    public function testItReturnRequestInstance()
    {
        $query = 'query';
        $pageNumber = 1;

        $parse = ParseRequestFactory::returnParseRequest($query, $pageNumber);

        $this->assertInstanceOf(Request::class, $parse);
    }

    public function testItRequestContainsParams()
    {
        $query = 'sdgsdfgsdfgsd';
        $pageNumber = 5;

        $parse = ParseRequestFactory::returnParseRequest($query, $pageNumber);
        $uri = (string)$parse->getUri();

        $this->assertStringContainsString('search.wb.ru', $uri);
        $this->assertEquals('GET', $parse->getMethod());
        $this->assertStringContainsString($query, $uri);
        $this->assertStringContainsString('page=' . $pageNumber, $uri);
    }
}
