<?php

namespace App\Http\Controllers\Admin\StorageLocation;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Rack;

class RackController extends Controller
{
    public function index(Request $request) {
        try {
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
            return view('storage_location.rack.dashboard', compact('racks'));

        } catch (\Exception $e) {
            \Log::error('Rack index error: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load racks'], 500);
            }

            return redirect()->back()
                            ->with('error', 'Failed to load racks');
        }
    }

    public function create() {
        return view('storage_location.rack.create');
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'rack_number' => 'required|string|max:255|unique:racks',
                'capacity' => 'nullable|integer|min:1',
            ]);

            $rack = Rack::create([
                'rack_number' => $request->rack_number,
                'capacity' => $request->capacity ?? 50,
            ]);

            return redirect()->route('rack.index')->with('success', 'Rack created successfully');
        } catch (\Exception $e) {
            \Log::error('Rack creation error: ' . $e->getMessage());

            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Rack creation failed: ' . $e->getMessage()]);
        }
    }

    public function edit($id) {
        $rack = Rack::find($id);
        return view('storage_location.rack.update', compact('rack'));
    }

    public function update(Request $request, $id) {
        try {
            $request->validate([
                'rack_number' => 'required|string|max:255|unique:racks,rack_number,' . $id,
                'capacity' => 'nullable|integer|min:1',
            ]);

            $rack = Rack::find($id);

            if (!$rack) {
                return redirect()->back()
                                ->withErrors(['error' => 'Rack not found']);
            }

            $rack->rack_number = $request->rack_number;
            $rack->capacity = $request->capacity ?? 50;
            $rack->save();

            return redirect()->route('rack.index')->with('success', 'Rack updated successfully');
        } catch (\Exception $e) {
            \Log::error('Rack update error: ' . $e->getMessage());

            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Rack update failed: ' . $e->getMessage()]);
        }
    }

    public function destroy($id) {
        try {
            $rack = Rack::findOrFail($id);

            if ($rack->storageLocations()->exists()) {
                return redirect()->route('rack.index')
                                ->withErrors(['error' => 'Cannot delete this rack because storage locations are still linked to it.']);
            }

            $rack->delete();

            return redirect()->route('rack.index')
                            ->with('success', 'Rack deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Rack deletion error: ' . $e->getMessage());

            return redirect()->back()
                            ->withErrors(['error' => 'Rack deletion failed: ' . $e->getMessage()]);
        }
    }
}
