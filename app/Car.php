<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    // variables
    protected $fillable = [
        'details',
        'created_by',
    ];

    protected $casts = [
        'id' => 'string',
        'details' => 'array',
    ];

    // scopes
    public function scopeOwnedByUser($query)
    {
        return $query->where('created_by', auth()->user()->id);
    }

    // relationships
    public function entries()
    {
        return $this->hasMany('App\Entry');
    }
}
