<?php

namespace App\Command\ParseHandler;

interface ParseHandlerInterface
{
    public function handle(ParseContext $parsePhrase): ParseResult;
}
