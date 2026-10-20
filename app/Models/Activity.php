<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    protected $fillable = [
        'activity_date',
        'raw_note',
    ];

    protected function casts(): array
    {
        return [
            'activity_date' => 'date',
        ];
    }
}