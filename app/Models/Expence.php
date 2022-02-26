<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expence extends Model
{
    use HasFactory;

    const TYPE = [
        1 => "EQUAL",
        2 => "EXACT",
        3 => "PERCENT",
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'amount',
        'type',
    ];
}
