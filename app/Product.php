<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use \Staudenmeir\EloquentHasManyDeep\HasRelationships;
    public $incrementing = false;

    protected $fillable = [
        'name', 'description', 'productImage', 'language', 'shortDescription', 'model', 'groupingId'
    ];

    public function fields()
    {
        return $this->belongsToMany('App\Field', 'product_field', 'product_id', 'field_id');
    }

    public function offers()
    {
        return $this->hasMany('App\Offer', 'sourceProductId');
    }

    public function pricechanges()
    {
        return $this->hasManyThrough('App\PriceHistory', 'App\Offer', 'sourceProductId');
    }

    public function prices()
    {
        return $this->hasManyDeepFromRelations($this->pricechanges(), (new PriceHistory)->price());
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category', 'category_product', 'product_id', 'category_id');
    }
}
