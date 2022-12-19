<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CalculationController extends Controller
{
    public function calculate(Request $request) {
        // TODO: Logic

        return [
            'success' => true,
            'result' => 0,
        ];
    }
}
