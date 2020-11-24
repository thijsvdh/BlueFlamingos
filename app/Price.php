<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'value', 'currency'
    ];

    public function pricechange()
    {
        return $this->hasMany('App\PriceHistory');
    }
}
