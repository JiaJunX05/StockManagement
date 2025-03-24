<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;
use App\Models\Barcode;
use App\Models\Product;
use App\Models\Image;
use App\Models\Category;
use App\Models\Storack;
use App\Models\Zone;
use App\Models\Rack;
use App\Models\User;

class ProductController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            $query = Product::with('barcode');

            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where('sku_code', 'like', '%' . $search . '%')
                      ->orWhereHas('barcode', function ($q) use ($search) {
                          $q->where('barcode_number', 'like', '%' . $search . '%');
                      });
            }

            $perPage = $request->input('per_page', 10);
            $page = $request->input('page', 1);

            $products = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => $products->total(),
                'recordsFiltered' => $products->total(),
                'data' => $products->items(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ]);
        }

        $products = Product::with('barcode')->get();
        return view('staff.dashboard', compact('products'));
    }

    public function showCreateForm() {
        $categories = Category::all();
        $zones = Zone::all();
        $storacks = Storack::with('rack')->get();
        return view('staff.create', compact('categories', 'zones', 'storacks'));
    }

    public function create(Request $request) {
        $request->validate([
            'feature' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255|unique:products,name',
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'sku_code' => 'required|string|max:255|unique:products,sku_code',
            'category_id' => 'required|exists:categories,id',
            'zone_id' => 'required|exists:zones,id',
            'rack_id' => 'nullable|exists:racks,id',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'barcode_number' => 'required|string|max:255|unique:barcodes,barcode_number',
        ]);

        // 处理封面图片
        $featureName = 'default.jpg';
        if ($request->hasFile('feature')) {
            $feature = $request->file('feature');
            $featureName = time() . uniqid() . '.' . $feature->getClientOriginalExtension();
            $feature->move(public_path('assets/features'), $featureName);
        }

        // 创建产品
        $products = Product::create([
            'feature' => 'features/' . $featureName,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'sku_code' => strtoupper($request->sku_code),
            'category_id' => $request->category_id,
            'zone_id' => $request->zone_id,
            'rack_id' => $request->rack_id,
        ]);

        // 处理产品图片
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $imageFile) {
                $imageName = time() . uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('assets/images'), $imageName);

                Image::create([
                    'image' => 'images/' . $imageName,
                    'product_id' => $products->id,
                ]);
            }
        }

        // 确保 barcode_number 存在
        if (empty($request->barcode_number)) {
            return redirect()->back()->withErrors(['barcode' => 'Barcode number is required.']);
        }

        // 生成条形码
        $barcodeFolder = public_path('assets/barcodes');
        if (!is_dir($barcodeFolder) && !mkdir($barcodeFolder, 0777, true) && !is_dir($barcodeFolder)) {
            return redirect()->back()->withErrors(['barcode' => 'Failed to create barcode directory.']);
        }

        $sanitizedSkuCode = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->sku_code);
        $barcodeImageName = $sanitizedSkuCode . '_' . time() . uniqid() . '.png';
        $barcodePath = $barcodeFolder . '/' . $barcodeImageName;

        $generator = new BarcodeGeneratorPNG();
        $barcodeData = $generator->getBarcode($request->barcode_number, $generator::TYPE_CODE_128, 3, 50);

        if (file_put_contents($barcodePath, $barcodeData) === false) {
            return redirect()->back()->withErrors(['barcode' => 'Failed to generate barcode image.']);
        }

        // 存入数据库
        Barcode::create([
            'barcode_image' => 'barcodes/' . $barcodeImageName,
            'barcode_number' => $request->barcode_number,
            'product_id' => $products->id,
        ]);

        return redirect()->route('staff.dashboard')->with('success', 'Product created successfully.');
    }

    public function view($id) {
        $product = Product::with(['barcode', 'zone', 'rack', 'images'])->findOrFail($id);
        return view('staff.view', compact('product'));
    }

    public function showUpdateForm($id) {
        $product = Product::with('barcode')->findOrFail($id);
        $categories = Category::all();
        $zones = Zone::all();
        $storacks = Storack::with('rack')->get();
        return view('staff.update', compact('product', 'categories', 'zones', 'storacks'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'feature' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'name' => 'required|string|max:255|unique:products,name,' . $id,
            'description' => 'nullable|string|max:1000',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'sku_code' => 'required|string|max:255|unique:products,sku_code,' . $id,
            'category_id' => 'required|exists:categories,id',
            'zone_id' => 'required|exists:zones,id',
            'rack_id' => 'nullable|exists:racks,id',
            'image.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'barcode_number' => 'required|string|max:255|unique:barcodes,barcode_number,' . $id . ',product_id',
        ]);

        $product = Product::findOrFail($id);
        $oldSkuCode = $product->sku_code;

        // ✅ 更新 `feature` 图片
        if ($request->hasFile('feature')) {
            $oldFeaturePath = public_path('assets/' . $product->feature);
            if ($product->feature && file_exists($oldFeaturePath)) {
                unlink($oldFeaturePath);
            }

            $featureName = time() . uniqid() . '.' . $request->file('feature')->getClientOriginalExtension();
            $request->file('feature')->move(public_path('assets/features'), $featureName);
            $product->feature = 'features/' . $featureName;
        }

        // ✅ 更新产品信息
        $product->update([
            'feature' => $product->feature,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'quantity' => $request->quantity,
            'sku_code' => strtoupper($request->sku_code),
            'category_id' => $request->category_id,
            'zone_id' => $request->zone_id,
            'rack_id' => $request->rack_id,
        ]);

        // ✅ 删除选中的图片
        if ($request->has('remove_image')) {
            foreach ($request->remove_image as $imageId) {
                $image = Image::find($imageId);
                if ($image) {
                    $imagePath = public_path('assets/' . $image->image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                    $image->delete();
                }
            }
        }

        // ✅ 添加新的图片
        if ($request->hasFile('image')) {
            foreach ($request->file('image') as $imageFile) {
                $imageName = time() . uniqid() . '.' . $imageFile->getClientOriginalExtension();
                $imageFile->move(public_path('assets/images'), $imageName);

                Image::create([
                    'image' => 'images/' . $imageName,
                    'product_id' => $product->id,
                ]);
            }
        }

        // ✅ 处理条形码
        $barcode = Barcode::where('product_id', $id)->first();
        $barcodeFolder = public_path('assets/barcodes');

        // 目录检查
        if (!is_dir($barcodeFolder) && !mkdir($barcodeFolder, 0777, true) && !is_dir($barcodeFolder)) {
            return redirect()->back()->withErrors(['barcode' => 'Failed to create barcode directory.']);
        }

        // ⚡ **优化：检查 `sku_code` 或 `barcode_number` 是否变更**
        $shouldGenerateNewBarcode = !$barcode || $barcode->barcode_number !== $request->barcode_number || $oldSkuCode !== $request->sku_code;

        if (!$barcode) {
            $barcode = new Barcode();
            $barcode->product_id = $id;
        } elseif ($shouldGenerateNewBarcode) {
            // **删除旧的条形码图片**
            if ($barcode->barcode_image && file_exists(public_path('assets/' . $barcode->barcode_image))) {
                unlink(public_path('assets/' . $barcode->barcode_image));
            }

            // **生成新条形码**
            $sanitizedSkuCode = preg_replace('/[^A-Za-z0-9_\-]/', '_', $request->sku_code);
            $barcodeImageName = $sanitizedSkuCode . '_' . time() . uniqid() . '.png';
            $barcodePath = $barcodeFolder . '/' . $barcodeImageName;

            $generator = new BarcodeGeneratorPNG();
            $barcodeData = $generator->getBarcode($request->barcode_number, $generator::TYPE_CODE_128, 3, 50);
            file_put_contents($barcodePath, $barcodeData);

            $barcode->barcode_image = 'barcodes/' . $barcodeImageName;
        }

        // ✅ 更新 `barcode_number`
        $barcode->barcode_number = $request->barcode_number;
        $barcode->save();

        return redirect()->route('product.view', $id)->with('success', 'Product updated successfully.');
    }

    public function showStockForm($id) {
        $product = Product::find($id);
        return view('staff.stock', compact('product'));
    }

    public function stockUpdate(Request $request, $id) {
        $product = Product::find($id);

        $request->validate([
            'stock_quantity' => 'required|integer|min:1',
            'status' => 'required|in:stock_in,stock_out',
        ]);

        $stock_quantity = $request->stock_quantity;
        $status = $request->status;

        if ($status === 'stock_in') {
            $product->quantity += $stock_quantity;
        } elseif ($status === 'stock_out') {
            if ($product->quantity < $stock_quantity) {
                return back()->withErrors('Stock quantity exceeds available stock.');
            }
            $product->quantity -= $stock_quantity;
        }

        $product->save();

        return redirect()->route('product.view', $id)->with('success', 'Stock updated successfully.');
    }

    // public function destroy($id) {
    //     $products = Product::findOrFail($id);

    //     if ($products->feature && file_exists(public_path('assets/' . $products->feature))) {
    //         unlink(public_path('assets/' . $products->feature));
    //     }

    //     $images = Image::where('product_id', $products->id)->get();
    //     foreach ($images as $image) {
    //         if (file_exists(public_path('assets/' . $image->image))) {
    //             unlink(public_path('assets/' . $image->image));
    //         }
    //         $image->delete();
    //     }

    //     // 查找关联的条形码
    //     $barcode = Barcode::where('product_id', $products->id)->first();

    //     if ($barcode) {
    //         // 删除条形码图片
    //         if (!empty($barcode->barcode_image) && file_exists(public_path('assets/' . $barcode->barcode_image))) {
    //             unlink(public_path('assets/' . $barcode->barcode_image));
    //         }

    //         // 删除条形码记录
    //         $barcode->delete();
    //     }

    //     // 删除产品记录
    //     $products->delete();

    //     return redirect()->route('staff.dashboard')->with('success', 'Product and its barcode deleted successfully.');
    // }
}
