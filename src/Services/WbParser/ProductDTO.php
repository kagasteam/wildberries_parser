<?php
declare(strict_types=1);

namespace App\Services\WbParser;

class ProductDTO
{
    public function __construct(
        private string $_name,
        private string $_brand,
        private int $_position,
        private string $_query
    ){
    }

    public function getName(): string
    {
        return $this->_name;
    }

    public function getBrand(): string
    {
        return $this->_brand;
    }

    public function getPosition(): int
    {
        return $this->_position;
    }

    public function getQuery(): string
    {
        return $this->_query;
    }
}
