<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Products;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
   public $products;

    public function index()
    {
        $category = Categories::with('Products')->get();
      
        return response()->json([$category]);
    }
    public function show($id)
    {
        $category = Categories::find($id);

        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Categories::find($id);
        $category->update($request->all());
        return response()->json($category, 200);
    }

    public function store(Request $request)
    {
        $categories = new Categories();

        $categories->name = $request->name;
        $categories->description = $request->description; 


        $categories->save();
        return response()->json([$categories, "message" => "Categoria Adicionada"], 200);
    }


    public function destroy($id)
    {
        Categories::destroy($id);
        return response()->json(null, 204);
    }
}
