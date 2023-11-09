<?php
declare(strict_types=1);

namespace App\Command\ParseHandler;

use Cake\Http\Client\Request;

class ParseRequestFactory extends Request
{
    private const METHOD_REQUEST = 'GET';
    private const BASE_URL = 'https://search.wb.ru/exactmatch/ru/common/v4/search?';

    private const BASE_QUERY_PARAMS = [
        'TestGroup' => 'no_test',
        'TestID' => 'no_test',
        'appType' => 1,
        'curr' => 'rub',
        'dest' => -1255942,
        'regions' => '80,38,4,64,83,33,68,70,69,30,86,75,40,1,66,110,22,31,48,71,114',
        'resultset' => 'catalog',
        'sort' => 'popular',
        'spp' => 0,
        'suppressSpellcheck' => 'false',
    ];

    private function __construct(
        string $url = '',
        string $method = self::METHOD_REQUEST,
        array $headers = [],
    ) {
        parent::__construct($url, $method, $headers);
    }

    /**
     * @param string $query
     * @param int $pageNumber
     * @return ParseRequestFactory
     */
    public static function returnParseRequest(string $query, $pageNumber): self
    {
        $params = http_build_query(
            array_merge(self::BASE_QUERY_PARAMS, ['query' => $query, 'page' => $pageNumber])
        );
        $url = self::BASE_URL . $params;

        return new self($url, self::METHOD_REQUEST);
    }
}
