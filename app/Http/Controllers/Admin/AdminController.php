<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class AdminController extends Controller
{
    // public function index() {
    //     $totalCount = User::count();
    //     $adminCount = User::where('role', 'admin')->count();
    //     $staffCount = User::where('role', 'staff')->count();

    //     return view('admin.dashboard', compact('totalCount', 'adminCount', 'staffCount'));
    // }

    public function showUserList(Request $request) {
        if ($request->ajax()) {
            $query = User::query();

            // 搜索条件
            if ($request->filled('search')) {
                $search = $request->input('search');
                $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            }

            // 角色筛选
            if ($request->filled('role') && $request->input('role') !== '') {
                $query->where('role', $request->input('role'));
            }

            $perPage = $request->input('perPage', 10);
            $page = $request->input('page', 1);

            $users = $query->paginate($perPage, ['*'], 'page', $page);

            // 计算分页显示信息
            $total = $users->total();
            $start = $total > 0 ? ($users->currentPage() - 1) * $perPage + 1 : 0;
            $end = min($start + $perPage - 1, $total);

            return response()->json([
                'data' => $users->items(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'total' => $total,
                'per_page' => $perPage,
                'from' => $start,
                'to' => $end,
                'pagination' => [
                    'showing_start' => $start,
                    'showing_end' => $end,
                    'total_count' => $total,
                    'has_more_pages' => $users->hasMorePages(),
                    'is_first_page' => $users->onFirstPage(),
                    'is_last_page' => $users->currentPage() === $users->lastPage()
                ]
            ]);
        }

        $users = User::all();
        return view('admin.users', compact('users'));
    }
}
