<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    // variables
    protected $fillable = [
        'entry',
        'created_by'
    ];

    protected $casts = [
        //'entry' => 'array',
    ];

    // relationships
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
