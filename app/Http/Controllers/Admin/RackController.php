<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rack;

class RackController extends Controller
{
    public function index(Request $request) {
        if ($request->ajax()) {
            $query = Rack::query();

            // 机架筛选
            if ($request->filled('filter') && $request->input('filter') !== '') {
                $query->where('id', $request->input('filter'));
            }

            $perPage = $request->input('perPage', 10);
            $page = $request->input('page', 1);

            $racks = $query->paginate($perPage, ['*'], 'page', $page);

            // 计算分页显示信息
            $total = $racks->total();
            $start = $total > 0 ? ($racks->currentPage() - 1) * $perPage + 1 : 0;
            $end = min($start + $perPage - 1, $total);

            return response()->json([
                'data' => $racks->items(),
                'current_page' => $racks->currentPage(),
                'last_page' => $racks->lastPage(),
                'total' => $total,
                'per_page' => $perPage,
                'from' => $start,
                'to' => $end,
                'pagination' => [
                    'showing_start' => $start,
                    'showing_end' => $end,
                    'total_count' => $total,
                    'has_more_pages' => $racks->hasMorePages(),
                    'is_first_page' => $racks->onFirstPage(),
                    'is_last_page' => $racks->currentPage() === $racks->lastPage()
                ],
            ]);
        }

        $racks = Rack::all();
        return view('rack.dashboard', compact('racks'));
    }

    public function showCreateForm() {
        return view('rack.create');
    }

    public function create(Request $request) {
        $request->validate([
            'rack_number' => 'required|string|max:255|unique:racks',
        ]);

        $racks = Rack::create([
            'rack_number' => $request->rack_number,
            // 'rack_number' =>  strtoupper($request->rack_number),
        ]);

        return redirect()->route('racks')->with('success', 'Rack created successfully');
    }

    public function showUpdateForm($id) {
        $racks = Rack::find($id);
        return view('rack.update', compact('racks'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'rack_number' => 'required|string|max:255|unique:racks,rack_number,' . $id,
        ]);

        $racks = Rack::find($id);
        $racks->rack_number = $request->rack_number;
        // $racks->rack_number = strtoupper($request->rack_number);
        $racks->save();

        return redirect()->route('racks')->with('success', 'Rack updated successfully');
    }

    public function destroy($id) {
        $racks = Rack::findOrFail($id);

        if ($racks->storacks()->exists()) {
            return redirect()->route('racks')->withErrors(['error' => 'Cannot delete this rack because storacks are still linked to it.']);
        }

        $racks->delete();

        return redirect()->route('racks')->with('success', 'Rack deleted successfully');
    }
}
