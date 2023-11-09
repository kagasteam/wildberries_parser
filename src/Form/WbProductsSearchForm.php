<?php
declare(strict_types=1);

namespace App\Form;

use Cake\Form\Form;
use Cake\Form\Schema;
use Cake\Validation\Validator;

class WbProductsSearchForm extends Form
{
    public const MSG_MUST_NOT_BE_EMPTY = 'Введите фразу для поиска!';
    public const MSG_MAX_STRING_LENGTH = 'Длина строки не должна превышать 255 символов';
    private const SEARCH_PHRASE = 'query';
    protected function _buildSchema(Schema $schema): Schema
    {
        return $schema
            ->addField(self::SEARCH_PHRASE, 'string');
    }

    public function validationDefault(Validator $validator): Validator
    {
        return $validator
            ->notEmptyString(self::SEARCH_PHRASE, self::MSG_MUST_NOT_BE_EMPTY)
            ->maxLength(self::SEARCH_PHRASE, 255, self::MSG_MAX_STRING_LENGTH);
    }

    protected function _execute(array $data): bool
    {
        return true;
    }
}
