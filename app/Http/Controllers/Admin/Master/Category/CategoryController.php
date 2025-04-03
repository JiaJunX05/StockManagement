<?php

namespace App\Http\Controllers\Admin\Master\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Master\Category\Category;

class CategoryController extends Controller
{
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $query = Category::query();

                // 分类名称筛选
                $query->when($request->filled('search'), function ($query) use ($request) {
                    $query->where('category_name', 'like', '%' . $request->search . '%');
                })
                // 分类筛选
                ->when($request->filled('category_id'), function ($query) use ($request) {
                    $query->where('id', $request->input('category_id'));
                });

                $perPage = $request->input('perPage', 10);
                $page = $request->input('page', 1);

                $categories = $query->paginate($perPage, ['*'], 'page', $page);

                // 计算分页显示信息
                $total = $categories->total();
                $start = $total > 0 ? ($categories->currentPage() - 1) * $perPage + 1 : 0;
                $end = min($start + $perPage - 1, $total);

                return response()->json([
                    'data' => $categories->items(),
                    'current_page' => $categories->currentPage(),
                    'last_page' => $categories->lastPage(),
                    'total' => $total,
                    'per_page' => $perPage,
                    'from' => $start,
                    'to' => $end,
                    'pagination' => [
                        'showing_start' => $start,
                        'showing_end' => $end,
                        'total_count' => $total,
                        'has_more_pages' => $categories->hasMorePages(),
                        'is_first_page' => $categories->onFirstPage(),
                        'is_last_page' => $categories->currentPage() === $categories->lastPage()
                    ],
                ]);
            }

            $categories = Category::all();
            return view('master.category.category.dashboard', compact('categories'));

        } catch (\Exception $e) {
            \Log::error('Category index error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load categories'], 500);
            }

            return redirect()->back()
                            ->withErrors(['error' => 'Failed to load categories']);
        }
    }

    public function create() {
        return view('master.category.category.create');
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'category_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_name' => 'required|string|max:255|unique:categories',
            ]);

            if (!$request->hasFile('category_image')) {
                return redirect()->back()
                                ->withErrors(['error' => 'Category image is required'])
                                ->withInput();
            }

            // 处理图片上传
            $image = $request->file('category_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = public_path('assets/images/categories');

            // 确保目录存在
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // 移动图片
            $image->move($directory, $imageName);

            // 创建记录
            $category = Category::create([
                'category_image' => 'categories/' . $imageName,
                'category_name' => $request->category_name,
            ]);

            return redirect()->route('category.index')
                            ->with('success', 'Category created successfully');
        } catch (\Exception $e) {
            \Log::error('Category store error: ' . $e->getMessage());
            // 如果出错， 删除已上传的图片
            if (isset($imageName) && file_exists($directory . '/' . $imageName)) {
                unlink($directory . '/' . $imageName);
            }

            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Category creation failed: ' . $e->getMessage()]);
        }
    }

    public function edit($id) {
        $category = Category::findOrFail($id);
        return view('master.category.category.update', compact('category'));
    }

    public function update(Request $request, $id) {
        try {
            $request->validate([
                'category_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'category_name' => 'required|string|max:255|unique:categories,category_name,' . $id,
            ]);

            $category = Category::findOrFail($id);

            if (!$category) {
                return redirect()->back()
                                ->withErrors(['error' => 'Category not found']);
            }

            if ($request->hasFile('category_image')) {
                // 删除旧图片
                if ($category->category_image) {
                    $imagePath = public_path('assets/images/' . $category->category_image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                // 处理新图片上传
                $image = $request->file('category_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $directory = public_path('assets/images/categories');

                // 确保目录存在
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $image->move($directory, $imageName);
                $category->category_image = 'categories/' . $imageName;
            }

            $category->category_name = $request->category_name;
            $category->save();

            return redirect()->route('category.index')
                            ->with('success', 'Category updated successfully');
        } catch (\Exception $e) {
            \Log::error('Category update error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Category update failed: ' . $e->getMessage()]);
        }
    }

    public function destroy($id) {
        try {
            $category = Category::findOrFail($id);

            // 检查是否有关联的分类
            if ($category->mappings()->exists()) {
                return redirect()->back()
                                ->withErrors(['error' => 'Cannot delete category with existing mappings']);
            }

            // 删除数据库记录
            if ($category->category_image) {
                $imagePath = public_path('assets/images/' . $category->category_image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // 删除数据库记录
            $category->delete();

            return redirect()->route('category.index')
                            ->with('success', 'Category deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Category deletion error: ' . $e->getMessage());
            return redirect()->back()
                            ->withErrors(['error' => 'Failed to delete category: ' . $e->getMessage()]);
        }
    }
}
