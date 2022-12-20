<?php

namespace Tests\Unit;

use App\Exceptions\Expression\DivisionByZeroException;
use App\Exceptions\Expression\InvalidOperatorException;
use App\Services\ExpressionService;
use PHPUnit\Framework\TestCase;

class ExpressionServiceTest extends TestCase
{
    private ExpressionService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = app(ExpressionService::class);
    }

    /**
     * @throws \App\Exceptions\Expression\ExpressionException
     */
    private function parseAndEvaluate(string $expression): float
    {
        $parsed = $this->service->parse($expression);

        return $this->service->evaluateBasic($parsed['left'], $parsed['operand'], $parsed['right']);
    }

    public function test_expression_string_with_no_spaces()
    {
        $parsed = $this->service->parse("1+2");

        $this->assertEquals("1 + 2", $parsed['expression']);
    }

    public function test_expression_string_with_odd_spaces()
    {
        $parsed = $this->service->parse("1 +2");

        $this->assertEquals("1 + 2", $parsed['expression']);
    }

    public function test_expression_string_with_normal_spaces()
    {
        $parsed = $this->service->parse("1 + 2");

        $this->assertEquals("1 + 2", $parsed['expression']);
    }

    public function test_parse_basic_addition()
    {
        $parsed = $this->service->parse("1 + 2");

        $this->assertEquals("1", $parsed['left']);
        $this->assertEquals("+", $parsed['operand']);
        $this->assertEquals("2", $parsed['right']);
    }

    public function test_parse_basic_subtraction()
    {
        $parsed = $this->service->parse("10 - 5");

        $this->assertEquals("10", $parsed['left']);
        $this->assertEquals("-", $parsed['operand']);
        $this->assertEquals("5", $parsed['right']);
    }

    public function test_parse_basic_multiplication()
    {
        $parsed = $this->service->parse("12 * 12");

        $this->assertEquals("12", $parsed['left']);
        $this->assertEquals("*", $parsed['operand']);
        $this->assertEquals("12", $parsed['right']);
    }

    public function test_parse_basic_division()
    {
        $parsed = $this->service->parse("64 / 8");

        $this->assertEquals("64", $parsed['left']);
        $this->assertEquals("/", $parsed['operand']);
        $this->assertEquals("8", $parsed['right']);
    }

    public function test_parse_basic_subtraction_with_negative()
    {
        $parsed = $this->service->parse("5 - -10");

        $this->assertEquals("5", $parsed['left']);
        $this->assertEquals("-", $parsed['operand']);
        $this->assertEquals("-10", $parsed['right']);
    }

    public function test_evaluate_basic_addition()
    {
        $value = $this->parseAndEvaluate("1 + 2");

        $this->assertEquals(3, $value);
    }

    public function test_evaluate_basic_subtraction()
    {
        $value = $this->parseAndEvaluate("10 - 5");

        $this->assertEquals(5, $value);
    }

    public function test_evaluate_basic_multiplication()
    {
        $value = $this->parseAndEvaluate("12 * 12");

        $this->assertEquals(144, $value);
    }

    public function test_evaluate_basic_division()
    {
        $value = $this->parseAndEvaluate("64 / 8");

        $this->assertEquals(8, $value);
    }

    public function test_evaluate_basic_subtraction_with_negative()
    {
        $value = $this->parseAndEvaluate("5 - -10");

        $this->assertEquals(15, $value);
    }

    public function test_evaluate_division_by_zero()
    {
        $this->expectException(DivisionByZeroException::class);

        $this->service->evaluateBasic("5",  "/", "0");
    }

    public function test_evaluate_invalid_operator()
    {
        $this->expectException(InvalidOperatorException::class);

        $this->service->evaluateBasic("10",  "_", "10");
    }
}
