<?php

namespace App\Http\Controllers\Admin\Master\Category;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Master\Category\Category;
use App\Models\Master\Category\SubCategory;
use App\Models\Master\Category\Mapping;

class MappingController extends Controller
{
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $query = Mapping::with(['category', 'subcategory']);

                // 分类筛选
                $query->when($request->filled('category_id'), function ($query) use ($request) {
                    $query->where('category_id', $request->category_id);
                })
                // 子分类筛选
                ->when($request->filled('subcategory_id'), function ($query) use ($request) {
                    $query->where('subcategory_id', $request->subcategory_id);
                });

                $perPage = $request->input('perPage', 10);
                $page = $request->input('page', 1);

                $mappings = $query->paginate($perPage, ['*'], 'page', $page);

                // 计算分页显示信息
                $total = $mappings->total();
                $start = $total > 0 ? ($mappings->currentPage() - 1) * $perPage + 1 : 0;
                $end = min($start + $perPage - 1, $total);

                return response()->json([
                    'data' => $mappings->items(),
                    'current_page' => $mappings->currentPage(),
                    'last_page' => $mappings->lastPage(),
                    'total' => $total,
                    'per_page' => $perPage,
                    'from' => $start,
                    'to' => $end,
                    'pagination' => [
                        'showing_start' => $start,
                        'showing_end' => $end,
                        'total_count' => $total,
                        'total_pages' => $mappings->lastPage(),
                    ],
                ]);
            }

            $categories = Category::all();
            $subcategories = SubCategory::all();
            return view('master.category.mapping.dashboard', compact('categories', 'subcategories'));
        } catch (\Exception $e) {
            \Log::error('Mapping index error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load data'], 500);
            }

            return redirect()->back()
                            ->withErrors(['error' => 'Failed to load data']);
        }
    }

    public function create() {
        $categories = Category::all();
        $subcategories = SubCategory::all();
        return view('master.category.mapping.create', compact('categories', 'subcategories'));
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'required|exists:subcategories,id',
            ]);

            // 检查是否已经存在相同的映射
            $exists = Mapping::where('category_id', $request->category_id)
                                    ->where('subcategory_id', $request->subcategory_id)
                                    ->exists();

            if ($exists) {
                return redirect()->back()
                                ->withErrors(['error' => 'This mapping already exists.']);
            }

            $mapping = Mapping::create([
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
            ]);

            return redirect()->route('mapping.index')
                            ->with('success', 'Mapping created successfully');
        } catch (\Exception $e) {
            \Log::error('Mapping store error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Failed to create category mapping']);
        }
    }

    public function edit($id) {
        $mapping = Mapping::find($id);
        $categories = Category::all();
        $subcategories = SubCategory::all();
        return view('master.category.mapping.update', compact('mapping', 'categories', 'subcategories'));
    }

    public function update(Request $request, $id) {
        try {
            $request->validate([
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'required|exists:subcategories,id',
            ]);

            $mapping = Mapping::findOrFail($id);

            // 检查是否已经存在相同的映射
            $exists = Mapping::where('category_id', $request->category_id)
                                    ->where('subcategory_id', $request->subcategory_id)
                                    ->where('id', '!=', $id)
                                    ->exists();

            if ($exists) {
                return redirect()->back()
                                ->withErrors(['error' => 'This mapping already exists.']);
            }

            $mapping->category_id = $request->category_id;
            $mapping->subcategory_id = $request->subcategory_id;
            $mapping->save();

            return redirect()->route('mapping.index')
                            ->with('success', 'Mapping updated successfully');
        } catch (\Exception $e) {
            \Log::error('Mapping update error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Failed to update category mapping']);
        }
    }

    public function destroy($id) {
        try {
            $mapping = Mapping::findOrFail($id);

            // 检查是否有关联的产品
            if ($mapping->category->products()->exists() || $mapping->subcategory->products()->exists()) {
                return redirect()->back()
                                ->withErrors(['error' => 'Cannot delete this mapping because products are still linked to its category or subcategory.']);
            }

            $mapping->delete();

            return redirect()->route('mapping.index')
                            ->with('success', 'Category Mapping deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Mapping destroy error: ' . $e->getMessage());
            return redirect()->back()
                            ->withErrors(['error' => 'Failed to delete mapping: ' . $e->getMessage()]);
        }
    }
}
