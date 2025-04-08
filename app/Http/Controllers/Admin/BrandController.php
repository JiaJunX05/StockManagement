<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Master\Brand;

class BrandController extends Controller
{
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $query = Brand::query();

                // 品牌名称筛选
                $query->when($request->filled('search'), function ($query) use ($request) {
                    $query->where('brand_name', 'like', '%' . $request->search . '%');
                })
                ->when($request->filled('brand_id'), function ($query) use ($request) {
                    $query->where('id', $request->input('brand_id'));
                });

                $perPage = $request->input('perPage', 10);
                $page = $request->input('page', 1);

                $brands = $query->paginate($perPage, ['*'], 'page', $page);

                // 计算分页显示信息
                $total = $brands->total();
                $start = $total > 0 ? ($brands->currentPage() - 1) * $perPage + 1 : 0;
                $end = min($start + $perPage - 1, $total);

                return response()->json([
                    'data' => $brands->items(),
                    'current_page' => $brands->currentPage(),
                    'last_page' => $brands->lastPage(),
                    'total' => $total,
                    'per_page' => $perPage,
                    'from' => $start,
                    'to' => $end,
                    'pagination' => [
                        'showing_start' => $start,
                        'showing_end' => $end,
                        'total_count' => $total,
                        'has_more_pages' => $brands->hasMorePages(),
                        'is_first_page' => $brands->onFirstPage(),
                        'is_last_page' => $brands->currentPage() === $brands->lastPage(),
                    ],
                ]);
            }

            $brands = Brand::all();
            return view('brand.dashboard', compact('brands'));
        } catch (\Exception $e) {
            \Log::error('Failed to load brands: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load brands'], 500);
            }

            return redirect()->back()
                            ->withErrors(['error' => 'Failed to load brands']);
        }
    }

    public function create() {
        return view('brand.create');
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'brand_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'brand_name' => 'required|string|max:255|unique:brands',
            ]);

            if (!$request->hasFile('brand_image')) {
                return redirect()->back()
                                ->withInput()
                                ->withErrors(['error' => 'Brand image is required']);
            }

            // 处理图片上传
            $image = $request->file('brand_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = public_path('assets/images/brands');

            // 确保目录存在
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // 移动图片
            $image->move($directory, $imageName);

            // 创建品牌
            $brand = Brand::create([
                'brand_image' => 'brands/' . $imageName,
                'brand_name' => $request->brand_name,
            ]);

            return redirect()->route('brand.index')
                            ->with('success', 'Brand created successfully');
        } catch (\Exception $e) {
            \Log::error('Brand creation error: ' . $e->getMessage());
            // 如果出错， 删除已上传的图片
            if (isset($imageName) && file_exists($directory . '/' . $imageName)) {
                unlink($directory . '/' . $imageName);
            }

            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Brand creation failed: ' . $e->getMessage()]);
        }
    }

    public function edit($id) {
        $brand = Brand::findOrFail($id);
        return view('brand.update', compact('brand'));
    }

    public function update(Request $request, $id) {
        try {
            $request->validate([
                'brand_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'brand_name' => 'required|string|max:255|unique:brands,brand_name,' . $id,
            ]);

            $brand = Brand::findOrFail($id);

            if (!$brand) {
                return redirect()->back()
                                ->withErrors(['error' => 'Brand not found']);
            }

            if ($request->hasFile('brand_image')) {
                // 删除旧图片
                if ($brand->brand_image) {
                    $imagePath = public_path('assets/images/' . $brand->brand_image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                // 处理新图片上传
                $image = $request->file('brand_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $directory = public_path('assets/images/brands');

                // 确保目录存在
                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $image->move($directory, $imageName);
                $brand->brand_image = 'brands/' . $imageName;
            }

            $brand->brand_name = $request->brand_name;
            $brand->save();

            return redirect()->route('brand.index')
                            ->with('success', 'Brand updated successfully');
        } catch (\Exception $e) {
            \Log::error('Brand update error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Brand update failed: ' . $e->getMessage()]);
        }
    }

    public function destroy($id) {
        try {
            $brand = Brand::findOrFail($id);

            // 检查是否有关联的产品
            if ($brand->products()->exists()) {
                return redirect()->back()
                                ->withErrors(['error' => 'Cannot delete this brand because products are still linked to it.']);
            }

            // 删除图片文件
            if ($brand->brand_image) {
                $imagePath = public_path('assets/images/' . $brand->brand_image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $brand->delete();

            return redirect()->route('brand.index')
                            ->with('success', 'Brand deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Brand deletion error: ' . $e->getMessage());
            return redirect()->back()
                            ->withErrors(['error' => 'Brand deletion failed: ' . $e->getMessage()]);
        }
    }
}
