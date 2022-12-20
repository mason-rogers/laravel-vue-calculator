<?php

namespace App\Http\Controllers;

use App\Http\Requests\CalculateRequest;
use App\Http\Resources\CalculationResource;
use App\Models\Calculation;
use App\Services\ExpressionService;
use Illuminate\Http\Request;

class CalculationController extends Controller
{
    public function __construct(
        private ExpressionService $expressionService
    ) {}

    public function index(Request $request) {
        return CalculationResource::collection(Calculation::all());
    }

    public function calculate(CalculateRequest $request) {
        $validated = $request->validated();

        $parsed = $this->expressionService->parse($validated['expression']);
        $evaluated = $this->expressionService->evaluateBasic($parsed['left'], $parsed['operand'], $parsed['right']);

        $created = Calculation::create([
            'expression' => $parsed['expression'],
            'result' => $evaluated,
        ]);

        // Maybe improve this, it's a bit of a hack - but it refreshes the value from the database
        // which means the result has the correct amount of precision instead of 16 digits after the decimal...
        $created->refresh();

        return [
            'success' => true,
            'result' => $created->result,
        ];
    }

    public function delete(Request $request, Calculation $calculation) {
        $calculation->delete();

        return response()->noContent();
    }

    public function clear(Request $request) {
        Calculation::query()->delete();

        return response()->noContent();
    }
}
