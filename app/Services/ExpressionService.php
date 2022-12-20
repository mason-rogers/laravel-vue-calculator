<?php

namespace App\Services;

use App\Exceptions\Expression\DivisionByZeroException;
use App\Exceptions\Expression\ExpressionException;
use App\Exceptions\Expression\InvalidOperatorException;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;

class ExpressionService {

    /**
     * Evaluate a basic expression, given a left & right number and an operand to execute
     *
     * @throws ExpressionException
     */
    public function evaluateBasic(float $left, string $operand, float $right): float {
        switch ($operand) {
            case '+':
                return $left + $right;
            case '-':
                return $left - $right;
            case '*':
                return $left * $right;
            case '/':
                if ($right == 0) throw new DivisionByZeroException();

                return $left / $right;
            default:
                throw new InvalidOperatorException();
        }
    }

    /**
     * Parse an expression to executable format
     *
     * @throws UnprocessableEntityHttpException
     */
    public function parse(string $expression): array {
        $matches = [];

        // I genuinely wrote this regex, pray for me...
        if (! preg_match('/^(^|[-+]?\d*\.?\d*)\s*([+\-*\/])\s*(^|[-+]?\d*\.?\d*)$/', $expression, $matches)) {
            throw new UnprocessableEntityHttpException('Unable to parse expression - are you sure it is valid?');
        }

        $left = (float) $matches[1];
        $operand = $matches[2];
        $right = (float) $matches[3];

        return [
            'expression' => "$matches[1] $matches[2] $matches[3]",

            'left' => $left,
            'operand' => $operand,
            'right' => $right,
        ];
    }
}
