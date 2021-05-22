<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Participants extends Model
{
    protected $fillable = [
        'name',
        'age',
        'address',
        'date_of_birth',
        'profession',
        'guests',
        'locality'
    ];
}
