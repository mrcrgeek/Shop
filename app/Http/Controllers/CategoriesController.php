<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoriesController extends Controller
{
    public function add_category(Request $request)
    {
        $request->validate([
            'category_name' => 'required|unique:App\Models\Category|max:50'
        ]);

        Category::create([
            'category_name' => $request->input('category_name')
        ]);

        return response()->json([
            'message' => 'Category Added Successfully'
        ], 200);
    }
}
