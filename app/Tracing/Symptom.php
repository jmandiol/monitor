<?php

namespace App\Tracing;

use Illuminate\Database\Eloquent\Model;

class Symptom extends Model
{
    protected $fillable = [
        'id', 'name'
    ];
}
