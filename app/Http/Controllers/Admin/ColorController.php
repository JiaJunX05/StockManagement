<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Color;

class ColorController extends Controller
{
    public function index(Request $request) {
        try {
            if ($request->ajax()) {
                $query = Color::query();

                // 颜色名称筛选
                $query->when($request->filled('search'), function ($query) use ($request) {
                    $query->where('color_name', 'like', '%' . $request->search . '%')
                        ->orWhere('hex_code', 'like', '%' . $request->search . '%')
                        ->orWhere('rgb_code', 'like', '%' . $request->search . '%');
                })
                // 颜色筛选
                ->when($request->filled('color_id'), function ($query) use ($request) {
                    $query->where('id', $request->input('color_id'));
                });

                $perPage = $request->input('perPage', 10);
                $page = $request->input('page', 1);

                $colors = $query->paginate($perPage, ['*'], 'page', $page);

                // 计算分页显示信息
                $total = $colors->total();
                $start = $total > 0 ? ($colors->currentPage() - 1) * $perPage + 1 : 0;
                $end = min($start + $perPage - 1, $total);

                return response()->json([
                    'data' => $colors->items(),
                    'current_page' => $colors->currentPage(),
                    'last_page' => $colors->lastPage(),
                    'total' => $total,
                    'per_page' => $perPage,
                    'from' => $start,
                    'to' => $end,
                    'pagination' => [
                        'showing_start' => $start,
                        'showing_end' => $end,
                        'total_count' => $total,
                        'has_more_pages' => $colors->hasMorePages(),
                        'is_first_page' => $colors->onFirstPage(),
                        'is_last_page' => $colors->currentPage() === $colors->lastPage()
                    ],
                ]);
            }

            $colors = Color::all();
            return view('color.dashboard', compact('colors'));

        } catch (\Exception $e) {
            \Log::error('Failed to load colors: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json(['error' => 'Failed to load colors'], 500);
            }

            return redirect()->back()
                            ->withErrors(['error' => 'Failed to load colors']);
        }
    }

    public function create() {
        return view('color.create');
    }

    public function store(Request $request) {
        try {
            $request->validate([
                'color_name' => 'required|string|max:255|unique:colors',
                'hex_code' => ['required', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
                'rgb_code' => 'required|string|max:255|unique:colors',
            ]);

            $color = Color::create([
                'color_name' => $request->color_name,
                'hex_code' => $request->hex_code,
                'rgb_code' => $request->rgb_code,
            ]);

            return redirect()->route('color.index')
                            ->with('success', 'Color created successfully');
        } catch (\Exception $e) {
            \Log::error('Color store error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Failed to create color']);
        }
    }

    public function edit($id) {
        $color = Color::findOrFail($id);
        return view('color.update', compact('color'));
    }

    public function update(Request $request, $id) {
        try {
            $request->validate([
                'color_name' => 'required|string|max:255|unique:colors,color_name,' . $id,
                'hex_code' => ['required', 'string', 'max:7', 'regex:/^#[0-9A-Fa-f]{6}$/'],
                'rgb_code' => 'required|string|max:255|unique:colors,rgb_code,' . $id,
            ]);

            $color = Color::findOrFail($id);

            if (!$color) {
                return redirect()->back()
                                ->withErrors(['error' => 'Color not found']);
            }

            $color->color_name = $request->color_name;
            $color->hex_code = $request->hex_code;
            $color->rgb_code = $request->rgb_code;
            $color->save();

            return redirect()->route('color.index')
                            ->with('success', 'Color updated successfully');
        } catch (\Exception $e) {
            \Log::error('Color update error: ' . $e->getMessage());
            return redirect()->back()
                            ->withInput()
                            ->withErrors(['error' => 'Failed to update color']);
        }
    }

    public function destroy($id) {
        try {
            $color = Color::findOrFail($id);

            // 检查是否有关联的产品
            if ($color->products()->exists()) {
                return redirect()->back()
                                ->withErrors(['error' => 'Cannot delete this color because products are still linked to it.']);
            }

            $color->delete();

            return redirect()->route('color.index')
                            ->with('success', 'Color deleted successfully');
        } catch (\Exception $e) {
            \Log::error('Color delete error: ' . $e->getMessage());
            return redirect()->back()
                            ->withErrors(['error' => 'Failed to delete color']);
        }
    }


}
