<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;

class AdminController extends Controller
{
    public function index() {
        $totalCount = User::count();
        $adminCount = User::where('role', 'admin')->count();
        $staffCount = User::where('role', 'staff')->count();

        return view('admin.dashboard', compact('totalCount', 'adminCount', 'staffCount'));
    }

    public function showUserList(Request $request) {
        if ($request->ajax()) {
            $query = User::query();

            if ($request->filled('search')) {
                    $search = $request->input('search');
                    $query->where(function ($query) use ($search) {
                    $query->where('name', 'like', "%$search%")
                        ->orWhere('email', 'like', "%$search%");
                });
            }

            $perPage = $request->input('perPage', 10);
            $page = $request->input('page', 1);

            $users = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'draw' => $request->input('draw'),
                'recordsTotal' => $users->total(),
                'recordsFiltered' => $users->total(),
                'data' => $users->items(),
                'currentPage' => $users->currentPage(),
                'lastPage' => $users->lastPage(),
                'total' => $users->total(),
            ]);
        }

        $users = User::all();
        return view('admin.users', compact('users'));
    }
}
