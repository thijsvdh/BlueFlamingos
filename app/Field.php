<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    protected $fillable = [
        'name', 'value'
    ];

    public function products()
    {
        return $this->belongsToMany('App\Field', 'product_field', 'field_id', 'product_id');
    }
}
