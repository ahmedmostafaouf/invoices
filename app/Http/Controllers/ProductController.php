<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function index()
    {
        $products=Product::all();
        $categories=Category::all();

        return view('product.product',compact('products','categories'));
    }




    public function store(ProductRequest $request)
    {

        try{
        $products =Product::create([
           'product_name'=>$request->product_name,
           'description'=>$request->description,
           'category_id'=>$request->category_id,
            "created_by"=>auth()->user()->name,
        ]);
            return redirect()->route('product.index')->with(['success'=>"تم الأضافة بنجاح"]);
        }catch (\Exception $ex){

            return redirect()->route('product.index')->with(['error' => 'حصل حطأ ما في الحفظ']);
        }

    }

    public function update(ProductRequest $request)
    {
        try{
           $id =Category::where('category_name',$request->category_name)->first()->id; // find id
           $products=Product::findOrFail($request->id);
           $products->update([
              "product_name"=>$request->product_name,
              "description"=>$request->description,
              "category_id"=>$id,
               "created_by"=>auth()->user()->name
           ]);
            return redirect()->route('product.index')->with(['success'=>"تم التعديل بنجاح"]);
        }catch (\Exception $ex){

            return redirect()->route('product.index')->with(['error' => 'حصل حطأ ما في الحفظ']);
        }
    }


    public function destroy(Request $request)
    {
        try {
            $id = $request->id;
            $products=Product::findOrFail($id);
            if(!$products){
                return redirect()->route('product.index')->with(['error' => 'القسم غير موجود']);
            }
            $products->delete();

            return redirect()->route('product.index')->with(['success' => 'تم الحذف بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('product.index')->with(['error' => 'حصل حطأ ما في الحفظ']);
        }

    }
}
