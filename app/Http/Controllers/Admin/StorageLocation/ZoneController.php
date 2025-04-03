<?php

namespace App\Http\Controllers\Admin\StorageLocation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Storage\Zone;

class ZoneController extends Controller
{
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $query = Zone::query();

                // 区域名称筛选
                $query->when($request->filled('search'), function ($query) use ($request) {
                    $query->where('location', 'like', '%' . $request->search . '%');
                })
                // 区域筛选
                ->when($request->filled('zone_id'), function ($query) use ($request) {
                    $query->where('id', $request->input('zone_id'));
                });

                $perPage = $request->input('perPage', 10);
                $page = $request->input('page', 1);

                $zones = $query->paginate($perPage, ['*'], 'page', $page);

                // 计算分页显示信息
                $total = $zones->total();
                $start = $total > 0 ? ($zones->currentPage() - 1) * $perPage + 1 : 0;
                $end = min($start + $perPage - 1, $total);

                return response()->json([
                    'data' => $zones->items(),
                    'current_page' => $zones->currentPage(),
                    'last_page' => $zones->lastPage(),
                    'total' => $total,
                    'per_page' => $perPage,
                    'from' => $start,
                    'to' => $end,
                    'pagination' => [
                        'showing_start' => $start,
                        'showing_end' => $end,
                        'total_count' => $total,
                        'has_more_pages' => $zones->hasMorePages(),
                        'is_first_page' => $zones->onFirstPage(),
                        'is_last_page' => $zones->currentPage() === $zones->lastPage()
                    ],
                ]);
            }

            $zones = Zone::all();
            return view('storage_location.zone.dashboard', compact('zones'));

        } catch (\Exception $e) {
            \Log::error('Failed to load zones: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load zones'], 500);
            }

            return redirect()->back()
                            ->withErrors(['error' => 'Failed to load zones']);
        }
    }

    public function create() {
        return view('storage_location.zone.create');
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'zone_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'zone_name' => 'required|string|max:255|unique:zones',
                'location' => 'required|string|max:255',
            ]);

            if (!$request->hasFile('zone_image')) {
                return redirect()->back()
                                ->withInput()
                                ->withErrors(['error' => 'Zone image is required']);
            }

            // 处理图片上传
            $image = $request->file('zone_image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $directory = public_path('assets/images/zones');

            // 确保目录存在
            if (!file_exists($directory)) {
                mkdir($directory, 0777, true);
            }

            // 移动图片
            $image->move($directory, $imageName);

            // 创建记录
            $zone = Zone::create([
                'zone_image' => 'zones/' . $imageName,
                'zone_name' => $request->zone_name,
                'location' => $request->location,
            ]);

            return redirect()->route('zone.index')
                            ->with('success', 'Zone created successfully');
        } catch (\Exception $e) {
            \Log::error('Zone creation error: ' . $e->getMessage());
            // 如果出错， 删除已上传的图片
            if (isset($imageName) && file_exists($directory . '/' . $imageName)) {
                unlink($directory . '/' . $imageName);
            }

            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Zone creation failed: ' . $e->getMessage()]);
        }
    }

    public function edit($id) {
        $zone = Zone::findOrFail($id);
        return view('storage_location.zone.update', compact('zone'));
    }

    public function update(Request $request, $id) {
        try {
            $request->validate([
                'zone_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'zone_name' => 'required|string|max:255|unique:zones,zone_name,' . $id,
                'location' => 'required|string|max:255',
            ]);

            $zone = Zone::findOrFail($id);

            if (!$zone) {
                return redirect()->back()
                                ->withErrors(['error' => 'Zone not found']);
            }

            if ($request->hasFile('zone_image')) {
                // 删除旧图片
                if ($zone->zone_image) {
                    $imagePath = public_path('assets/images/' . $zone->zone_image);
                    if (file_exists($imagePath)) {
                        unlink($imagePath);
                    }
                }

                // 处理新图片上传
                $image = $request->file('zone_image');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $directory = public_path('assets/images/zones');

                if (!file_exists($directory)) {
                    mkdir($directory, 0777, true);
                }

                $image->move($directory, $imageName);
                $zone->zone_image = 'zones/' . $imageName;
            }

            $zone->zone_name = $request->zone_name;
            $zone->location = $request->location;
            $zone->save();

            return redirect()->route('zone.index')
                            ->with('success', 'Zone updated successfully');
        } catch (\Exception $e) {
            \Log::error('Zone update error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Zone update failed: ' . $e->getMessage()]);
        }
    }

    public function destroy($id) {
        try {
            $zone = Zone::findOrFail($id);

            // 检查是否有关联的存储位置
            if ($zone->locations()->exists()) {
                return redirect()->back()
                                ->withErrors(['error' => 'Cannot delete this zone because storage locations are still linked to it.']);
            }

            // 删除图片文件
            if ($zone->zone_image) {
                $imagePath = public_path('assets/images/' . $zone->zone_image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // 删除数据库记录
            $zone->delete();

            return redirect()->route('zone.index')
                            ->with('success', 'Zone deleted successfully');

        } catch (\Exception $e) {
            \Log::error('Zone deletion error: ' . $e->getMessage());
            return redirect()->back()
                            ->withErrors(['error' => 'Failed to delete zone: ' . $e->getMessage()]);
        }
    }
}
