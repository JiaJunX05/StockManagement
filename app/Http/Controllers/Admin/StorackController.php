<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Storack;
use App\Models\Rack;
use App\Models\Zone;

class StorackController extends Controller
{
    public function index(Request $request) {
        // 处理 AJAX 请求
        if ($request->ajax()) {
            $query = Storack::with(['zone', 'rack']); // 预加载 Zone 和 Rack 关联

            // 机架筛选
            if ($request->filled('filter') && $request->input('filter') !== '') {
                $query->where('id', $request->input('filter'));
            }

            // 分页参数
            $perPage = $request->input('length', 10);
            $page = $request->input('page', 1);

            // 获取分页数据
            $storacks = $query->paginate($perPage, ['*'], 'page', $page);

            // 计算分页显示信息
            $total = $storacks->total();
            $start = $total > 0 ? ($storacks->currentPage() - 1) * $perPage + 1 : 0;
            $end = min($start + $perPage - 1, $total);

            // 返回 DataTables 兼容的 JSON 响应
            return response()->json([
                'data' => $storacks->items(),
                'current_page' => $storacks->currentPage(),
                'last_page' => $storacks->lastPage(),
                'total' => $total,
                'per_page' => $perPage,
                'from' => $start,
                'to' => $end,
                'pagination' => [
                    'showing_start' => $start,
                    'showing_end' => $end,
                    'total_count' => $total,
                    'has_more_pages' => $storacks->hasMorePages(),
                    'is_first_page' => $storacks->onFirstPage(),
                    'is_last_page' => $storacks->currentPage() === $storacks->lastPage()
                ],
            ]);
        }

        // 非 AJAX 请求，返回初始视图
        $zones = Zone::all(); // 获取所有区域数据（用于下拉框）
        $racks = Rack::all(); // 获取所有货架数据（用于下拉框）
        return view('storack.dashboard', compact('zones', 'racks'));
    }

    public function showCreateForm() {
        $zones = Zone::all();
        $racks = Rack::all();
        return view('storack.create', compact('zones', 'racks'));
    }

    public function create(Request $request) {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'rack_id' => 'required|exists:racks,id',
        ]);

        // 检查是否已经存在相同的组合
        $exists = Storack::where('zone_id', $request->zone_id)
                         ->where('rack_id', $request->rack_id)
                         ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['error' => 'This Zone and Rack combination already exists.']);
        }

        $storacks = Storack::create([
            'zone_id' => $request->zone_id,
            'rack_id' => $request->rack_id,
        ]);

        return redirect()->route('storacks')->with('success', 'Storack created successfully');
    }

    public function showUpdateForm($id) {
        $storacks = Storack::findOrFail($id);
        $zones = Zone::all();
        $racks = Rack::all();
        return view('storack.update', compact('storacks', 'zones', 'racks'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'zone_id' => 'required|exists:zones,id',
            'rack_id' => 'required|exists:racks,id',
        ]);

        $storacks = Storack::findOrFail($id);

        // 检查是否已经存在相同的组合
        $exists = Storack::where('zone_id', $request->zone_id)
                         ->where('rack_id', $request->rack_id)
                         ->where('id', '!=', $id)
                         ->exists();

        if ($exists) {
            return redirect()->back()->withErrors(['error' => 'This Zone and Rack combination already exists.']);
        }

        $storacks->zone_id = $request->zone_id;
        $storacks->rack_id = $request->rack_id;
        $storacks->save();

        return redirect()->route('storacks')->with('success', 'Storack updated successfully');
    }

    public function destroy($id) {
        $storacks = Storack::findOrFail($id);
        $storacks->delete();

        if ($storacks->products()->exists()) {
            return redirect()->route('storacks')->withErrors(['error' => 'Cannot delete this storack because products are still linked to it.']);
        }

        return redirect()->route('storacks')->with('success', 'Storack deleted successfully.');
    }
}
