<?php

namespace App\Http\Controllers;

use App\Product;
use App\Price;
use DebugBar\DebugBar;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
class ProductController extends Controller
{

    public function start()
    {
        return view('welcome');
    }

    /**
     * Display a listing of the resource.
     *
     * @param int $variant
     * @return \Illuminate\Http\Response
     */
    public function index($variant)
    {
        $products = Product::orderBy('updated_at', 'DESC')->with(['prices' => function ($q) use ($variant) {
            $q->where('variant', $variant)->orderBy('created_at');
        }])->has('prices')->get();
        foreach ($products as $key => $value) {
            if(!count($value->prices)){
                unset($products[$key]);
            }
        }
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 6;
        $currentPageSearchResults = $products->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $entries = new LengthAwarePaginator($currentPageSearchResults, count($products), $perPage,null,['path'=>'api/products/'.$variant]);
        return $entries;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($request->isMethod('put')) {
            $id = $request->input('id');
            $variant = $request->input('prices.variant');
            $product = Product::with('prices')->findOrFail($id);
            $price = Price::where('product_id',$id)->where('variant',$variant)->first();
            if(!$price){
                $price = new Price();
            }
        } else {
            $product = new Product();
            $price = new Price();
        }
        $product->name = $request->input('name');
        $product->description = $request->input('description');
        $product->save();
        $price->price = $request->input('prices.price');
        $price->product_id = $product->id;
        $price->variant = $request->input('prices.variant');
        $product->prices()->save($price);
        return $product;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);
        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id,$variant = null)
    {
        $product = Product::findOrFail($id);
        if(!is_null($variant)){
            $price = Price::FindOrFail($id)->where('variant',$variant)->where('product_id',$id);
            $price->delete();
            return $product;
        }
        if ($product->prices()->delete()) {
            return $product;
        }
    }
}
