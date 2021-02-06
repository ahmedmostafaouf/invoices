<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return view('category.category',compact('categories'));
    }

    public function store(CategoryRequest $request)
    {
        try {
            $categories=Category::create([
                "category_name"=>$request->category_name,
                "description"=>$request->description,
                "created_by"=>auth()->user()->name,

            ]);
            return redirect()->route('category.index')->with(['success'=>"تم الأضافة بنجاح"]);
        }catch (\Exception $ex){

            return redirect()->route('category.index')->with(['error' => 'حصل حطأ ما في الحفظ']);
        }

    }
    public function update(CategoryRequest $request)
    {
        try {
           $id = $request->id;
           $categories = Category::findOrFail($id);
           if(!$categories){
               return redirect()->route('category.index')->with(['error' => 'القسم غير موجود']);
           }
         Category::where('id',$id)->update([
             "category_name"=>$request->category_name,
             "description"=>$request->description,
         ]);
            return redirect()->route('category.index')->with(['success' => 'تم التعديل بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('category.index')->with(['error' => 'حصل حطأ ما في الحفظ']);
        }
    }


    public function destroy(Request $request)
    {
        try {
            $id = $request->id;
            $categories = Category::findOrFail($id);
            if(!$categories){
                return redirect()->route('category.index')->with(['error' => 'القسم غير موجود']);
            }
            $categories->delete();

            return redirect()->route('category.index')->with(['success' => 'تم الحذف بنجاح']);
        }catch (\Exception $ex){
            return redirect()->route('category.index')->with(['error' => 'حصل حطأ ما في الحفظ']);
        }
    }
}
