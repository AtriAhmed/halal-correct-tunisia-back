<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductsController extends Controller
{

    public function index()
{
    $products = Product::all();
    return response()->json([
        'products'=>$products,
    ],200);
}

    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'category_id'=>'required|max:191',
            'name'=>'required|max:191',
        ]);
        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->getMessageBag(),
            ],422);
        }
        else{
            $product = new Product;
            $product->category_id = $request->input('category_id');
            $product->name = $request->input('name');
            $product->save();

            return response()->json([
                'message'=>'Product added successfully',
            ],200);
        }
    }

    public function edit($id)
{
    $product = Product::find($id);
    if($product)
    {
        return response()->json([
            'product'=>$product
        ],200);
    }
    else
    {
        return response()->json([
            'message'=>'Produit not found'
        ],404);
    }
}

public function update(Request $request,$id){
    $validator = Validator::make($request->all(),[
        'category_id'=>'required|max:191',
        'name'=>'required|max:191',
    ]);
    if($validator->fails()){
        return response()->json([
            'errors'=>$validator->getMessageBag(),
        ],422);
    }
    else{
        $product =  Product::find($id);
        if($product)
        {
        $product->category_id = $request->input('category_id');
        $product->name = $request->input('name');
        $product->update();

        return response()->json([
            'message'=>'Product updated successfully',
        ],200);
    }
    else
    {
        return response()->json([
            'message'=>'Produit not found',
        ],404);
    }
    }
}

public function getCategoriesWithProducts()
    {
        $data = [];
        $categories = Category::where('id','!=',1)->get();
        foreach($categories as $category){
            $products = Product::where('category_id',$category->id)->get();
            if($products){
                array_push($data,[$products]);
            }
        }
        return response()->json([
            'data'=>$data,
        ],200);
    }

}
