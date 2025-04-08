<?php

namespace App\Http\Controllers\Admin\CategoryMappings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Master\Category\SubCategory;

class SubCategoryController extends Controller
{
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $query = SubCategory::query();

                // 子分类名称筛选
                $query->when($request->filled('search'), function ($query) use ($request) {
                    $query->where('subcategory_name', 'like', '%' . $request->search . '%');
                })
                // 子分类筛选
                ->when($request->filled('subcategory_id'), function ($query) use ($request) {
                    $query->where('id', $request->input('subcategory_id'));
                });

                $perPage = $request->input('perPage', 10);
                $page = $request->input('page', 1);

                $subCategories = $query->paginate($perPage, ['*'], 'page', $page);

                // 计算分页显示信息
                $total = $subCategories->total();
                $start = $total > 0 ? ($subCategories->currentPage() - 1) * $perPage + 1 : 0;
                $end = min($start + $perPage - 1, $total);

                return response()->json([
                    'data' => $subCategories->items(),
                    'current_page' => $subCategories->currentPage(),
                    'last_page' => $subCategories->lastPage(),
                    'total' => $total,
                    'per_page' => $perPage,
                    'from' => $start,
                    'to' => $end,
                    'pagination' => [
                        'showing_start' => $start,
                        'showing_end' => $end,
                        'total_count' => $total,
                        'has_more_pages' => $subCategories->hasMorePages(),
                        'is_first_page' => $subCategories->onFirstPage(),
                        'is_last_page' => $subCategories->currentPage() === $subCategories->lastPage()
                    ],
                ]);

            }

            $subCategories = SubCategory::all();
            return view('category_mappings.cat_subcategories.dashboard', compact('subCategories'));

        } catch (\Exception $e) {
            \Log::error('SubCategory index error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load subcategories'], 500);
            }

            return redirect()->back()
                            ->withErrors(['error' => 'Failed to load subcategories']);
        }
    }

    public function create() {
        return view('category_mappings.cat_subcategories.create');
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'subcategory_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'subcategory_name' => 'required|string|max:255|unique:subcategories',
            ]);

            if (!$request->hasFile('subcategory_image')) {
                return redirect()->back()
                                ->withErrors(['error' => 'Subcategory image is required'])
                                ->withInput();
            }

            // 处理图片上传
            $image = $request->file('subcategory_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = public_path('assets/images/subcategories');

            // 确保目录存在
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // 移动图片
            $image->move($directory, $imageName);

            // 创建记录
            $subCategory = SubCategory::create([
                'subcategory_image' => 'subcategories/' . $imageName,
                'subcategory_name' => $request->subcategory_name,
            ]);

            return redirect()->route('subcategory.index')->with('success', 'Subcategory created successfully');
        } catch (\Exception $e) {
            \Log::error('SubCategory store error: ' . $e->getMessage());
            // 如果出错， 删除已上传的图片
            if (isset($imageName) && file_exists($directory . '/' . $imageName)) {
                unlink($directory . '/' . $imageName);
            }

            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Subcategory creation failed: ' . $e->getMessage()]);
        }
    }

    public function edit($id) {
        $subcategory = SubCategory::findOrFail($id);
        return view('category_mappings.cat_subcategories.update', compact('subcategory'));
    }

    public function update(Request $request, $id) {
        try {
            $request->validate([
                'subcategory_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'subcategory_name' => 'required|string|max:255|unique:subcategories,subcategory_name,' . $id,
            ]);

            $subcategory = SubCategory::findOrFail($id);

            if (!$subcategory) {
                return redirect()->back()
                                ->withErrors(['error' => 'Subcategory not found']);
            }

            if ($request->hasFile('subcategory_image')) {
                // 删除旧图片
                if ($subcategory->subcategory_image) {
                    $imagePath = public_path('assets/images/' . $subcategory->subcategory_image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                // 处理新图片上传
                $image = $request->file('subcategory_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $directory = public_path('assets/images/subcategories');

                // 确保目录存在
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                // 移动图片
                $image->move($directory, $imageName);
                $subcategory->subcategory_image = 'subcategories/' . $imageName;
            }

            $subcategory->subcategory_name = $request->subcategory_name;
            $subcategory->save();

            return redirect()->route('subcategory.index')->with('success', 'Subcategory updated successfully');
        } catch (\Exception $e) {
            \Log::error('SubCategory update error: ' . $e->getMessage());
            return redirect()->back()
                            ->whtiInput()
                            ->withErrors(['error' => 'SubCategory update failed: ' . $e->getMessage()]);
        }
    }

    public function destroy($id) {
        try {
            $subcategory = SubCategory::findOrFail($id);

            // 检查是否有关联的分类
            if ($subcategory->mappings()->exists()) {
                return redirect()->back()
                                ->withErrors(['error' => 'Cannot delete subcategory with existing mappings']);
            }

            // 删除数据库记录
            if ($subcategory->subcategory_image) {
                $imagePath = public_path('assets/images/' . $subcategory->subcategory_image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // 删除数据库记录
            $subcategory->delete();

            return redirect()->route('subcategory.index')
                            ->with('success', 'SubCategory deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Category deletion error: ' . $e->getMessage());
            return redirect()->back()
                            ->withErrors(['error' => 'Failed to delete subcategory: ' . $e->getMessage()]);
        }
    }
}
