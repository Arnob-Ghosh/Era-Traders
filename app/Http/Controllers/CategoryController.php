<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Log;

class CategoryController extends Controller
{
    public function create()
    {
        return view('category.category-create');
    }

    public function store(Request $req)
    {
        $messages = [
            'categoryname.required' => "Category name is required.",
            'categoryname.unique' => "Category name already in use.",
        ];

        $validator = Validator::make($req->all(), [
            'categoryname' => 'required|unique:categories,category_name',
        ], $messages);

        if ($validator->passes()) {
            $category = new Category;

            $category->category_name = $req->categoryname;
            $category->code = $req->categorycode;
            $category->save();

            return response()->json([
                'status' => 200,
                'message' => 'Category created successfully!'
            ]);
        }

        return response()->json(['error' => $validator->errors()]);
    }

    public function list(Request $request)
    {
        $category = Category::all();

        if ($request->ajax()) {
            return response()->json([
                'category' => $category,
            ]);
        }
    }

    public function edit($id)
    {
        // Find the category by its ID
        $category = Category::find($id);

        if ($category) {
            return response()->json([
                'status' => 200,
                'category' => $category,
            ]);
        }
    }

    public function update(Request $req, $id)
    {
        $messages = [
            'categoryname.required' => "Category name is required.",
        ];

        $validator = Validator::make($req->all(), [
            'categoryname' => 'required',
        ], $messages);

        if ($validator->passes()) {
            $category = Category::find($id);
            $category->category_name = $req->categoryname;
            $category->code = $req->categorycode;
            $category->save();

            $productcategory = ProductCategory::where('category_id', $id)->get();
            foreach ($productcategory as $product) {
                $product->category_name = $req->categoryname;
                $product->save();
            }

            return response()->json([
                'status' => 200,
                'message' => 'Category updated successfully'
            ]);
        }

        return response()->json(['error' => $validator->errors()]);
    }

    public function destroy($id)
    {
        Category::find($id)->delete($id);

        return response()->json([
            'status' => 200,
            'message' => 'Deleted successfully!'
        ]);
    }
}
