<?php
declare(strict_types=1);

namespace App\Controller;

use App\Form\WbProductsSearchForm;
use App\Services\WbParser\Repository\WbProductsRepositoryInterface;

class WbSearchController extends AppController
{
    public function index(WbProductsRepositoryInterface $repository)
    {
        $productSearchForm = new WbProductsSearchForm();
        $userQuery = $this->request->getQuery('query');
        $products = null;

        $currentPage = $this->request->getQuery('page') ?? 1;
        $currentPage = max(1, min(10, $currentPage));
        $limit = 100;
        $offset = $limit * ($currentPage - 1);

        if ($this->request->is('POST')) {
            if ($productSearchForm->execute($this->request->getData())) {
                $userQuery = (string)$productSearchForm->getData('query');
            }
        }

        if ($userQuery !== null) {
            try {
                $products = $repository->get(mb_strtolower($userQuery), $limit, $offset);
            } catch (\Throwable $exception) {
                $this->log($exception->getMessage());
            }

            if ($products !== null) {
                if (count($products) > 0) {
                    $this->Flash->success('Найдено товаров по запросу: ' . $userQuery);
                } else {
                    $this->Flash->error('Не найдено совпадений по запросу: ' . $userQuery);
                }
            }
        }

        $this->set('productSearchForm', $productSearchForm);
        $this->set('userQuery', $userQuery);
        $this->set('products', $products);
        $this->set('currentPage', $currentPage);
    }
}
