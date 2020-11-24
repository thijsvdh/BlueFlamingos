<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PriceHistory extends Model
{
    protected $table = 'pricechanges';

    protected $fillable = [
        'date'
    ];

    public function offer()
    {
        return $this->belongsTo('App\Offer');
    }

    public function price()
    {
        return $this->belongsTo('App\Price');
    }
}
