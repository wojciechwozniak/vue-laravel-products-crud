<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = ['price','variant','product_id'];
    protected $primaryKey = 'product_id';
    public function product()
    {
        return $this->belongsTo('App\Product');
    }
}
