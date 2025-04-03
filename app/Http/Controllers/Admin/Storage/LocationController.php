<?php

namespace App\Http\Controllers\Admin\Storage;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Storage\Zone;
use App\Models\Storage\Rack;
use App\Models\Storage\Location;

class LocationController extends Controller
{
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $query = Location::with(['zone', 'rack']);

                // 区域筛选
                $query->when($request->filled('zone_id'), function ($query) use ($request) {
                    $query->where('zone_id', $request->zone_id);
                })
                // 货架筛选
                ->when($request->filled('rack_id'), function ($query) use ($request) {
                    $query->where('rack_id', $request->rack_id);
                });

                $perPage = $request->input('perPage', 10);
                $page = $request->input('page', 1);

                // 获取分页数据
                $locations = $query->paginate($perPage);

                // 计算分页显示信息
                $total = $locations->total();
                $start = $total > 0 ? ($locations->currentPage() - 1) * $perPage + 1 : 0;
                $end = min($start + $perPage - 1, $total);

                // 返回 DataTables 兼容的 JSON 响应
                return response()->json([
                    'data' => $locations->items(),
                    'current_page' => $locations->currentPage(),
                    'last_page' => $locations->lastPage(),
                    'total' => $total,
                    'per_page' => $perPage,
                    'from' => $start,
                    'to' => $end,
                    'pagination' => [
                        'showing_start' => $start,
                        'showing_end' => $end,
                        'total_count' => $total,
                        'total_pages' => $locations->lastPage(),
                    ],
                ]);

            }

            $zones = Zone::all();
            $racks = Rack::all();
            return view('storage.location.dashboard', compact('zones', 'racks'));
        } catch (\Exception $e) {
            \Log::error('Location index error: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load locations'], 500);
            }

            return redirect()->back()
                            ->withErrors(['error' => 'Failed to load locations']);
        }
    }

    public function create() {
        $zones = Zone::all();
        $racks = Rack::all();
        return view('storage.location.create', compact('zones', 'racks'));
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'zone_id' => 'required|exists:zones,id',
                'rack_id' => 'required|exists:racks,id',
            ]);

            // 检查是否已经存在相同的组合
            $exists = Location::where('zone_id', $request->zone_id)
                                    ->where('rack_id', $request->rack_id)
                                    ->exists();

            if ($exists) {
                return redirect()->back()
                                ->withErrors(['error' => 'This Zone and Rack combination already exists.']);
            }

            $location = Location::create([
                'zone_id' => $request->zone_id,
                'rack_id' => $request->rack_id,
            ]);

            return redirect()->route('location.index')
                            ->with('success', 'Storage location created successfully');
        } catch (\Exception $e) {
            \Log::error('Location store error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Failed to create storage location']);
        }
    }

    public function edit($id) {
        $location = Location::find($id);
        $zones = Zone::all();
        $racks = Rack::all();
        return view('storage.location.update', compact('location', 'zones', 'racks'));
    }

    public function update(Request $request, $id) {
        try {
            $request->validate([
                'zone_id' => 'required|exists:zones,id',
                'rack_id' => 'required|exists:racks,id',
            ]);

            $location = Location::findOrFail($id);

            // 检查是否已经存在相同的组合
            $exists = Location::where('zone_id', $request->zone_id)
                                    ->where('rack_id', $request->rack_id)
                                    ->where('id', '!=', $id)
                                    ->exists();

            if ($exists) {
                return redirect()->back()->withErrors(['error' => 'This Zone and Rack combination already exists.']);
            }

            $location->zone_id = $request->zone_id;
            $location->rack_id = $request->rack_id;
            $location->save();

            return redirect()->route('location.index')
                            ->with('success', 'Storage location updated successfully');
        } catch (\Exception $e) {
            \Log::error('Location update error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Failed to update storage location']);
        }
    }

    public function destroy($id) {
        try {
            $location = Location::findOrFail($id);

            // 检查是否有关联的产品
            if ($location->zone->products()->exists() || $location->rack->products()->exists()) {
                return redirect()->back()
                                ->withErrors(['error' => 'Cannot delete this location because products are still linked to its zone or rack.']);
            }

            $location->delete();

            return redirect()->route('location.index')
                            ->with('success', 'Storage location deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Location destroy error: ' . $e->getMessage());
            return redirect()->back()
                            ->withErrors(['error' => 'Failed to delete storage location: ' . $e->getMessage()]);
        }
    }
}
