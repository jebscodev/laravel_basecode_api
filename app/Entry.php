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
        'entry' => 'array',
    ];

    // scope
    public function scopeOwnedByUser($query)
    {
        return $query->where('created_by', auth()->user()->id);
    }

    // relationships
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
