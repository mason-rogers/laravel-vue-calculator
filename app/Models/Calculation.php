<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Calculation
 *
 * @property int $id
 * @property string $expression
 * @property float $result
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Calculation newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calculation newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Calculation query()
 * @method static \Illuminate\Database\Eloquent\Builder|Calculation whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calculation whereExpression($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calculation whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calculation whereResult($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Calculation whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Calculation extends Model
{
    use HasFactory;

    protected $fillable = [
        'expression',
        'result'
    ];
}
