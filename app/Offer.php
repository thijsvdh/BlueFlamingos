<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'feedId', 'productUrl', 'modified', 'sourceProductId', 'programLogo', 'programName', 'availability', 'deliveryTime', 'shippingCost'
    ];

    public function pricechanges()
    {
        return $this->hasMany('App\PriceHistory');
    }
}
