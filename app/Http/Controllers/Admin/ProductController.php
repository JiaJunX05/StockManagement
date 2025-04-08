<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\Master\Brand;
use App\Models\Master\Color;
use App\Models\Master\Category\Category;
use App\Models\Master\Category\SubCategory;
use App\Models\Master\Category\Mapping;
use App\Models\Product\Product;
use App\Models\Product\Image;
use App\Models\Product\Barcode;
use App\Models\Storage\Zone;
use App\Models\Storage\Rack;
use App\Models\Storage\Location;
use Illuminate\Support\Str;
use App\Models\User;

class ProductController extends Controller
{
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $query = Product::with('barcode', 'category', 'subCategory', 'brand', 'color', 'zone', 'rack');

                // 搜索
                $query->when($request->filled('search'), function ($query) use ($request) {
                    $query->where('name', 'like', '%' . $request->search . '%')
                          ->orWhere('sku_code', 'like', '%' . $request->search . '%')
                          ->orWhereHas('barcode', function ($q) use ($request) {
                            $q->where('barcode_number', 'like', '%' . $request->search . '%');
                          });
                })
                // 分类筛选
                ->when($request->filled('category'), function ($query) use ($request) {
                    $query->where('category_id', $request->category);
                })
                // 子分类筛选（多选）
                ->when($request->filled('subcategories') && is_array($request->subcategories), function ($query) use ($request) {
                    $query->whereIn('subcategory_id', $request->subcategories);
                })
                // 品牌筛选（多选）
                ->when($request->filled('brands') && is_array($request->brands), function ($query) use ($request) {
                    $query->whereIn('brand_id', $request->brands);
                });

                // 分页
                $perPage = $request->input('perPage', 12);
                $page = $request->input('page', 1);

                $products = $query->paginate($perPage, ['*'], 'page', $page);

                // 获取每个分类和品牌的产品数量
                $subcategoryCounts = SubCategory::withCount('products')->get()
                    ->mapWithKeys(function ($subcategory) {
                        return [$subcategory->id => $subcategory->products_count];
                    });

                $brandCounts = Brand::withCount('products')->get()
                    ->mapWithKeys(function ($brand) {
                        return [$brand->id => $brand->products_count];
                    });

                // 计算分页显示信息
                $total = $products->total();
                $start = $total > 0 ? ($products->currentPage() - 1) * $perPage + 1 : 0;
                $end = min($start + $perPage - 1, $total);

                return response()->json([
                    'data' => $products->items(),
                    'current_page' => $products->currentPage(),
                    'last_page' => $products->lastPage(),
                    'total' => $total,
                    'per_page' => $perPage,
                    'from' => $start,
                    'to' => $end,
                    'pagination' => [
                        'showing_start' => $start,
                        'showing_end' => $end,
                        'total_count' => $total,
                        'has_more_pages' => $products->hasMorePages(),
                        'is_first_page' => $products->onFirstPage(),
                        'is_last_page' => $products->currentPage() === $products->lastPage(),
                    ],
                    'subcategory_counts' => $subcategoryCounts,
                    'brand_counts' => $brandCounts
                ]);
            }

            // 获取基础数据，包括产品数量统计
            $products = Product::with('barcode')->get();
            $categories = Category::all();
            $subcategories = SubCategory::withCount('products')->get();
            $brands = Brand::withCount('products')->get();

            return view('product.dashboard', compact('products', 'categories', 'subcategories', 'brands'));

        } catch (\Exception $e) {
            \Log::error('Product index error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load products'], 500);
            }

            return redirect()->back()
                            ->withErrors(['error' => 'Failed to load products']);
        }
    }

    public function create() {
        $categories = Category::all();
        $zones = Zone::all();
        $mappings = Mapping::with('subcategory')->get();
        $locations = Location::with('rack')->get();
        $brands = Brand::all();
        $colors = Color::all();
        return view('product.create', compact('categories', 'zones', 'mappings', 'locations', 'brands', 'colors'));
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'cover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'sku_code' => 'required|string|max:255|unique:products,sku_code',
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'required|exists:subcategories,id',
                'brand_id' => 'required|exists:brands,id',
                'color_id' => 'required|exists:colors,id',
                'zone_id' => 'required|exists:zones,id',
                'rack_id' => 'nullable|exists:racks,id',
                'detail_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'barcode_number' => 'required|string|max:255|unique:barcodes,barcode_number',
            ]);

            if (!$request->hasFile('cover_image')) {
                return redirect()->back()
                                ->withInput()
                                ->withErrors(['error' => 'Cover image is required']);
            }

            // 处理封面图片
            $coverImage = $request->file('cover_image');
            $coverName = time() . uniqid() . '.' . $coverImage->getClientOriginalExtension();
            $coverPath = public_path('assets/images/products/covers');

            // 确保目录存在
            if (!file_exists($coverPath)) {
                mkdir($coverPath, 0777, true);
            }

            // 移动封面图片
            $coverImage->move($coverPath, $coverName);

            // 创建产品
            $product = Product::create([
                'cover_image' => 'covers/' . $coverName,
                'name' => $request->name,
                'description' => $request->description,
                'price' => $request->price,
                'quantity' => $request->quantity,
                'sku_code' => strtoupper($request->sku_code),
                'category_id' => $request->category_id,
                'subcategory_id' => $request->subcategory_id,
                'brand_id' => $request->brand_id,
                'color_id' => $request->color_id,
                'zone_id' => $request->zone_id,
                'rack_id' => $request->rack_id,
                'user_id' => auth()->user()->id,
            ]);

            // 处理详情图片
            if ($request->hasFile('detail_image')) {
                foreach ($request->file('detail_image') as $image) {
                    $imageName = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                    $imagePath = public_path('assets/images/products/details');

                    // 确保目录存在
                    if (!file_exists($imagePath)) {
                        mkdir($imagePath, 0777, true);
                    }

                    // 移动详情图片
                    $image->move($imagePath, $imageName);

                    // 创建图片记录
                    Image::create([
                        'detail_image' => 'details/' . $imageName,
                        'product_id' => $product->id,
                    ]);
                }
            }

            // 处理条形码
            if (empty($request->barcode_number)) {
                return redirect()->back()
                                ->withInput()
                                ->withErrors(['error' => 'Barcode number is required']);
            }

            // 生成条形码
            $barcodeFolder = public_path('assets/images/products/barcodes');
            if (!is_dir($barcodeFolder) && !mkdir($barcodeFolder, 0777, true) && !is_dir($barcodeFolder)) {
                return redirect()->back()
                                ->withInput()
                                ->withErrors(['error' => 'Failed to create barcode directory']);
            }

            $sanitizedSkuCode = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->sku_code);
            $barcodeImageName = $sanitizedSkuCode . '_' . time() . uniqid() . '.png';
            $barcodePath = $barcodeFolder . '/' . $barcodeImageName;

            $generator = new BarcodeGeneratorPNG();
            $barcodeData = $generator->getBarcode($request->barcode_number, $generator::TYPE_CODE_128, 3, 50);

            if (file_put_contents($barcodePath, $barcodeData) === false) {
                return redirect()->back()
                                ->withInput()
                                ->withErrors(['error' => 'Failed to generate barcode image']);
            }

            // 存入数据库
            Barcode::create([
                'barcode_image' => 'barcodes/' . $barcodeImageName,
                'barcode_number' => $request->barcode_number,
                'product_id' => $product->id,
            ]);

            return redirect()->route('product.index')
                            ->with('success', 'Product created successfully');
        } catch (\Exception $e) {
            \Log::error('Product store error: ' . $e->getMessage());
            // 如果出错，删除已上传的图片
            if (isset($coverName) && file_exists($coverPath . '/' . $coverName)) {
                unlink($coverPath . '/' . $coverName);
            }

            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Product creation failed: ' . $e->getMessage()]);
        }
    }

    public function view($id) {
        $product = Product::with(['barcode', 'zone', 'rack', 'images', 'category', 'subcategory', 'brand', 'color'])->findOrFail($id);
        return view('product.view', compact('product'));
    }

    public function edit($id) {
        $product = Product::with('barcode')->findOrFail($id);
        $categories = Category::all();
        $zones = Zone::all();
        $mappings = Mapping::with('subcategory')->get();
        $locations = Location::with('rack')->get();
        $brands = Brand::all();
        $colors = Color::all();
        return view('product.update', compact('product', 'categories', 'zones', 'mappings', 'locations', 'brands', 'colors'));
    }

    public function update(Request $request, $id) {
        try {
            $request->validate([
                'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'name' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000',
                'price' => 'required|numeric|min:0',
                'quantity' => 'required|integer|min:0',
                'sku_code' => 'required|string|max:255|unique:products,sku_code,' . $id,
                'category_id' => 'required|exists:categories,id',
                'subcategory_id' => 'required|exists:subcategories,id',
                'brand_id' => 'required|exists:brands,id',
                'color_id' => 'required|exists:colors,id',
                'zone_id' => 'required|exists:zones,id',
                'rack_id' => 'nullable|exists:racks,id',
                'detail_image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'barcode_number' => 'required|string|max:255|unique:barcodes,barcode_number,' . $id . ',product_id',
            ]);

            $product = Product::findOrFail($id);
            $oldSkuCode = $product->sku_code;

            // 处理封面图片
            if ($request->hasFile('cover_image')) {
                // 删除旧图片
                if ($product->cover_image) {
                    $oldImagePath = public_path('assets/images/products/' . $product->cover_image);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // 处理新图片上传
                $image = $request->file('cover_image');
                $coverName = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                $coverPath = public_path('assets/images/products/covers');

                // 确保目录存在
                if (!file_exists($coverPath)) {
                    mkdir($coverPath, 0777, true);
                }

                $image->move($coverPath, $coverName);
                $product->cover_image = 'covers/' . $coverName;
            }

            // 更新产品信息
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->quantity = $request->quantity;
            $product->sku_code = strtoupper($request->sku_code);
            $product->category_id = $request->category_id;
            $product->subcategory_id = $request->subcategory_id;
            $product->brand_id = $request->brand_id;
            $product->color_id = $request->color_id;
            $product->zone_id = $request->zone_id;
            $product->rack_id = $request->rack_id;
            $product->save();

            // 处理删除图片
            if ($request->has('remove_image')) {
                foreach ($request->remove_image as $imageId) {
                    $image = Image::find($imageId);
                    if ($image && $image->product_id == $product->id) {  // 确保图片属于当前产品
                        $imagePath = public_path('assets/images/products/' . $image->detail_image);
                        if (file_exists($imagePath)) {
                            unlink($imagePath);
                        }
                        $image->delete();
                    }
                }
            }

            // 处理详情图片
            if ($request->hasFile('detail_image')) {
                foreach ($request->file('detail_image') as $image) {
                    $detailName = time() . uniqid() . '.' . $image->getClientOriginalExtension();
                    $detailPath = public_path('assets/images/products/details');

                    // 确保目录存在
                    if (!file_exists($detailPath)) {
                        mkdir($detailPath, 0777, true);
                    }

                    $image->move($detailPath, $detailName);

                    // 创建图片记录
                    Image::create([
                        'detail_image' => 'details/' . $detailName,
                        'product_id' => $product->id,
                    ]);
                }
            }

            // 处理条形码
            $barcode = Barcode::where('product_id', $product->id)->first();
            $barcodeFolder = public_path('assets/images/products/barcodes');

            // ⚡ 优化：检查 sku_code 或 barcode_number 是否变更
            $shouldGenerateNewBarcode = !$barcode ||
                                      ($barcode && $barcode->barcode_number !== $request->barcode_number) ||
                                      strtoupper($oldSkuCode) !== strtoupper($request->sku_code);

            if ($shouldGenerateNewBarcode) {
                // 生成新条形码
                if (!is_dir($barcodeFolder) && !mkdir($barcodeFolder, 0777, true) && !is_dir($barcodeFolder)) {
                    return redirect()->back()
                                    ->withInput()
                                    ->withErrors(['error' => 'Failed to create barcode directory']);
                }

                $sanitizedSkuCode = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->sku_code);
                $barcodeName = $sanitizedSkuCode . '_' . time() . uniqid() . '.png';
                $barcodePath = $barcodeFolder . '/' . $barcodeName;

                $generator = new BarcodeGeneratorPNG();
                $barcodeData = $generator->getBarcode($request->barcode_number, $generator::TYPE_CODE_128, 3, 50);

                if (file_put_contents($barcodePath, $barcodeData) === false) {
                    return redirect()->back()
                                    ->withInput()
                                    ->withErrors(['error' => 'Failed to generate barcode image']);
                }

                if ($barcode) {
                    // 删除旧的条形码图片
                    if ($barcode->barcode_image) {
                        $oldBarcodePath = public_path('assets/images/products/' . $barcode->barcode_image);
                        if (file_exists($oldBarcodePath)) {
                            unlink($oldBarcodePath);
                        }
                    }

                    // 更新现有条形码记录
                    $barcode->barcode_image = 'barcodes/' . $barcodeName;
                    $barcode->barcode_number = $request->barcode_number;
                    $barcode->save();
                } else {
                    // 创建新的条形码记录
                    Barcode::create([
                        'barcode_image' => 'barcodes/' . $barcodeName,
                        'barcode_number' => $request->barcode_number,
                        'product_id' => $product->id,
                    ]);
                }
            }

            return redirect()->route('product.view', $product->id)
                            ->with('success', 'Product updated successfully');
        } catch (\Exception $e) {
            \Log::error('Product update error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Product update failed: ' . $e->getMessage()]);
        }
    }

    public function destroy($id) {
        try {
            $product = Product::findOrFail($id);

            // 删除封面图片
            if ($product->cover_image) {
                $coverPath = public_path('assets/images/products/covers/' . basename($product->cover_image));
                if (file_exists($coverPath)) {
                    unlink($coverPath);
                }
            }

            // 删除详情图片
            $images = Image::where('product_id', $product->id)->get();
            foreach ($images as $image) {
                $detailPath = public_path('assets/images/products/details/' . basename($image->detail_image));
                if (file_exists($detailPath)) {
                    unlink($detailPath);
                }
                $image->delete();
            }

            // 删除条形码
            $barcode = Barcode::where('product_id', $product->id)->first();
            if ($barcode) {
                $barcodePath = public_path('assets/images/products/barcodes/' . basename($barcode->barcode_image));
                if (file_exists($barcodePath)) {
                    unlink($barcodePath);
                }
                $barcode->delete();
            }

            $product->delete();

            return redirect()->route('product.index')
                            ->with('success', 'Product deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Product deletion error: ' . $e->getMessage());
            return redirect()->back()
                            ->withErrors(['error' => 'Failed to delete product: ' . $e->getMessage()]);
        }
    }
}
