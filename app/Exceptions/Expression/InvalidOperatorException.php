<?php

namespace App\Exceptions\Expression;

class InvalidOperatorException extends ExpressionException
{
    public function __construct()
    {
        parent::__construct("You have tried to use an invalid operator. Valid operators are + - * /");
    }
}
