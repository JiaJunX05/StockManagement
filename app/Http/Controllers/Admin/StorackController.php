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
        // 获取所有区域和货架数据（用于下拉框）
        $zones = Zone::all();
        $racks = Rack::all();

        // 处理 AJAX 请求
        if ($request->ajax()) {
            // 查询 Storack 数据，预加载 Zone 和 Rack 关联
            $query = Storack::with(['zone', 'rack']);

            // Zone 过滤
            if ($request->filled('zone_id')) {
                $query->where('zone_id', $request->input('zone_id'));
            }

            // Rack 过滤
            if ($request->filled('rack_id')) {
                $query->where('rack_id', $request->input('rack_id'));
            }

            // 分页参数
            $perPage = $request->input('length', 10);
            $page = $request->input('page', 1);

            // 获取分页数据
            $storacks = $query->paginate($perPage, ['*'], 'page', $page);

            // 返回 DataTables 兼容的 JSON 响应
            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => $storacks->total(),
                'recordsFiltered' => $storacks->total(),
                'data' => $storacks->items(),
                'current_page' => $storacks->currentPage(),
                'last_page' => $storacks->lastPage(),
                'total' => $storacks->total(),
            ]);
        }

        // 非 AJAX 请求，返回初始视图
        $storacks = Storack::with(['zone', 'rack'])->get();
        return view('storack.dashboard', compact('storacks', 'zones', 'racks'));
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

        return redirect()->route('storack.list')->with('success', 'Storack created successfully');
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

        return redirect()->route('storack.list')->with('success', 'Storack updated successfully');
    }

    public function destroy($id) {
        $storacks = Storack::findOrFail($id);
        $storacks->delete();

        return redirect()->route('storack.list')->with('success', 'Storack deleted successfully.');
    }
}
