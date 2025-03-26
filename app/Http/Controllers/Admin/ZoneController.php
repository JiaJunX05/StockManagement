<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone;

class ZoneController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            $query = Zone::query();

            // 区域筛选
            if ($request->filled('filter') && $request->input('filter') !== '') {
                $query->where('id', $request->input('filter'));
            }

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
        return view('zone.dashboard', compact('zones'));
    }

    public function showCreateForm() {
        return view('zone.create');
    }

    public function create(Request $request) {
        $request->validate([
            'zone_name' => 'required|string|max:255|unique:zones',
        ]);

        $zones = Zone::create([
            'zone_name' => $request->zone_name,
            // 'zone_name' =>  strtoupper($request->zone_name),
        ]);

        return redirect()->route('zones')->with('success', 'Zone created successfully');
    }

    public function showUpdateForm($id) {
        $zones = Zone::find($id);
        return view('zone.update', compact('zones'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'zone_name' => 'required|string|max:255|unique:zones,zone_name,' . $id,
        ]);

        $zones = Zone::find($id);
        $zones->zone_name = $request->zone_name;
        // $zones->zone_name = strtoupper($request->zone_name);
        $zones->save();

        return redirect()->route('zones')->with('success', 'Zone updated successfully');
    }

    public function destroy($id) {
        $zones = Zone::findOrFail($id);

        if ($zones->storacks()->exists()) {
            return redirect()->route('zones')->withErrors(['error' => 'Cannot delete this zone because storacks are still linked to it.']);
        }

        $zones->delete();

        return redirect()->route('zones')->with('success', 'Zone deleted successfully');
    }
}
