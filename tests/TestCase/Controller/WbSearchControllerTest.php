<?php
declare(strict_types=1);

namespace App\Test\TestCase\Controller;

use App\Form\WbProductsSearchForm;
use Cake\TestSuite\IntegrationTestTrait;
use Cake\TestSuite\TestCase;

class WbSearchControllerTest extends TestCase
{
    use IntegrationTestTrait;

    private const ROUTE = '/wbsearch';

    public function testDisplay(): void
    {
        $this->get(self::ROUTE);
        $this->assertResponseOk();
        $this->assertResponseContains('WildBerries Parser');
        $this->assertResponseContains('Введите фразу для поиска');
        $this->assertResponseContains('<input type="text"');
        $this->assertResponseContains('<html>');
    }

    public function testSendingPostData(): void
    {
        $data = [
             'query' => 'query',
        ];
        $this->enableCsrfToken();
        $this->post(self::ROUTE, $data);
        $this->assertResponseSuccess();
    }

    public function testFormValidationErrors(): void
    {
        $this->enableCsrfToken();
        $this->post(self::ROUTE, ['query' => '']);
        $this->assertResponseContains(WbProductsSearchForm::MSG_MUST_NOT_BE_EMPTY);

        $this->post(self::ROUTE, ['query' => random_bytes(555)]);
        $this->assertResponseContains(WbProductsSearchForm::MSG_MAX_STRING_LENGTH);
    }
}
