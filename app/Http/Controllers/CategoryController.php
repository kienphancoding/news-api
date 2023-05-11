<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\NewsCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::all();

        return response()->json($category, 200)->header('Content-Type', 'application/json');
    }

    public function show($id)
    {
        $category = Category::find($id);

        return response()->json($category, 200)->header('Content-Type', 'application/json');
    }

    public function create(Request $request)
    {
        $category = new Category();

        $category->name = $request->name;
        $category->path = $request->path;
        $category->save();
    }

    public function delete($id)
    {
        Category::destroy($id);

        NewsCategory::where("category_id", $id)->delete();
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        $category->name = $request->name;
        $category->path = $request->path;
        $category->save();
    }
}
