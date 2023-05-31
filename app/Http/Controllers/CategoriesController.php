<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends Controller
{

public function index()
{
    $categories = Category::all();
    return response()->json([
        'categories'=>$categories,
    ],200);
}

public function getOne($id)
{
    $category = Category::find($id);
    if($category)
    {
        return response()->json([
            'category'=>$category
        ],200);
    }
    else
    {
        return response()->json([
            'message'=>'Categorie not found !'
        ],404);
    }
}

public function update(Request $request, $id){
    if($id === 1 | $id==1 | $id === '1' | $id == '1'){
        return response()->json([
            'message'=>'\'Unclassified\' category cannot be changed !',
        ],401);
    }else{

    $validator = Validator::make($request->all(), [
        'name'=>'required|max:191',
    ]);

    if($validator->fails())
    {
        return response()->json([
            'errors'=>$validator->getMessageBag(),
        ],422);
    }
    else
    {
        $category = Category::find($id);
        if($category)
        {
            $category->name = $request->input('name');
            $category->save();
            return response()->json([
                'message'=>'Category updated successfully',
            ],200);
        }
        else
        {
            return response()->json([
                'message'=>'Categoriy not found !'
            ],404);
        }
    }
}
}

    public function create(Request $request){
        $validator = Validator::make($request->all(),[
            'name'=>'required|max:191',
        ]);
        if($validator->fails()){
            return response()->json([
                'errors'=>$validator->getMessageBag(),
            ],400);
        }
        else
        {
            $category = new Category;
            $category->name = $request->input('name');
            $category->save();
            return response()->json([
                'message'=>'Category added successfully',
            ],200);
        }
    }

    public function destroy($id)
    {
        if($id === 1 | $id==1 | $id === '1' | $id == '1'){
            return response()->json([
                'message'=>'\'Unclassified\' category cannot be deleted !',
            ],401);
        }
        else{
        $category = Category::find($id);
        $products = Product::where('category_id',$id)->get();
        foreach($products as $product){
            $product->category_id = 1;
            $product->save();
        }
        if($category)
        {
            $category->delete();
            return response()->json([
                'message'=>'Category deleted successfully',
            ],200);
        }
        else
        {
            return response()->json([
                'message'=>'Category not found !',
            ],404);
        }
        }
    }
}
