<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $product = Products::all();

        return response()->json($product);
    }
    public function show($id)
    {
        $product = Products::find($id);

        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $product = Products::find($id);
        $product->update($request->all());
        return response()->json($product, 200);
    }

    public function store(Request $request)
    {
        $products = new Products();

        $products->name = $request->name;
        $products->description = $request->description;
        $products->price = $request->price;
        $products->image = $request->image;
        $products->category_id = $request->category_id;
 
        $products->save();
        return response()->json([$products, "message" => "Produto Adicionado"], 200);
    }


    public function destroy($id)
    {
        Products::destroy($id);
        return response()->json(null, 204);
    }
}
