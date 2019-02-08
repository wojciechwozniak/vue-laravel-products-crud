<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function prices()
    {
        return $this->hasMany('App\Price');
    }
    public function getProduct($id){
        return $this->with('prices')->get()->pluck('price_id','product_id');
    }
    public function setName($id){
//        return $this->
    }
    public function getName($id)
    {
        return $this->find(['id' => $id])->pluck('name')->first();
    }
//    public function getPrice($id){
//        Product::find()
//    }
}
