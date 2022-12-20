<?php

namespace Tests\Feature;

use App\Models\Calculation;
use Tests\TestCase;

class CalculationControllerTest extends TestCase
{
    public function test_calculation_history()
    {
        $this->postJson('/api/calculations', [
            'expression' => '42 + 58',
        ]);

        $this->postJson('/api/calculations', [
            'expression' => '12 * 12',
        ]);

        $this->assertDatabaseCount(Calculation::class, 2);

        $res = $this->getJson('/api/calculations');

        $res->assertStatus(200);
        $res->assertJson([
            'data' => [
                [
                    'expression' => '42 + 58',
                    'result' => 100,
                ],
                [
                    'expression' => '12 * 12',
                    'result' => 144,
                ]
            ]
        ]);
    }

    public function test_calculation_is_created()
    {
        $res = $this->postJson('/api/calculations', [
            'expression' => '42 + 58',
        ]);

        $res->assertStatus(200);
        $res->assertJson([
            'success' => true,
            'result' => [
                'expression' => '42 + 58',
                'result' => 100,
            ]
        ]);

        $this->assertDatabaseHas(Calculation::class, [
            'id' => $res->json('result.id'),
        ]);
    }

    public function test_deleting_calculation()
    {
        $this->postJson('/api/calculations', [
            'expression' => '42 + 58',
        ]);

        $calculationRes = $this->postJson('/api/calculations', [
            'expression' => '12 * 12',
        ]);

        $this->assertDatabaseHas(Calculation::class, [
            'id' => $calculationRes->json('result.id'),
        ]);

        $res = $this->deleteJson("/api/calculations/{$calculationRes->json('result.id')}");

        $res->assertStatus(204);
        $res->assertNoContent();

        $this->assertDatabaseMissing(Calculation::class, [
            'id' => $calculationRes->json('result.id'),
        ]);
    }

    public function test_clearing_calculations()
    {
        $this->postJson('/api/calculations', [
            'expression' => '42 + 58',
        ]);

        $this->postJson('/api/calculations', [
            'expression' => '12 * 12',
        ]);

        $res = $this->deleteJson('/api/calculations');

        $res->assertStatus(204);
        $res->assertNoContent();

        $this->assertDatabaseCount(Calculation::class, 0);
    }
}
