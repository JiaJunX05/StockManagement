<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::all();
        return view('category.dashboard', compact('categories'));
    }

    public function showCreateForm() {
        return view('category.create');
    }

    public function create(Request $request) {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories',
        ]);

        $categories = Category::create([
            'category_name' => $request->category_name,
        ]);

        return redirect()->route('category.list')->with('success', 'Category created successfully.');
    }

    public function showUpdateForm($id) {
        $category = Category::find($id);
        return view('category.update', compact('category'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'category_name' => 'required|string|max:255|unique:categories,category_name,' . $id,
        ]);

        $category = Category::find($id);
        $category->category_name = $request->input('category_name');
        $category->save();

        return redirect()->route('category.list')->with('success', 'Category updated successfully.');
    }

    public function destroy($id) {
        $category = Category::find($id);

        if ($category->products()->exists()) {
            return redirect()->route('category.list')->withErrors(['error' => 'Cannot delete category with associated products.']);
        }

        $category->delete();

        return redirect()->route('category.list')->with('success', 'Category deleted successfully.');
    }
}
