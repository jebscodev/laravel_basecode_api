<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    // variables
    protected $fillable = [
        'entry',
        'created_by',
        'car_id'
    ];

    protected $casts = [
        'id' => 'string',
        'entry' => 'array',
    ];

    // scope
    public function scopeOfCar($query, $car)
    {
        return $query->where('car_id', $car);
    }

    // relationships
    public function car()
    {
        return $this->belongsTo('App\Car');
    }
}
