<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rack;

class RackController extends Controller
{
    public function index() {
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

        return redirect()->route('rack.list')->with('success', 'Rack created successfully');
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

        return redirect()->route('rack.list')->with('success', 'Rack updated successfully');
    }

    public function destroy($id) {
        $racks = Rack::findOrFail($id);

        if ($racks->storacks()->exists()) {
            return redirect()->route('rack.list')->withErrors(['error' => 'Cannot delete this rack because storacks are still linked to it.']);
        }

        $racks->delete();

        return redirect()->route('rack.list')->with('success', 'Rack deleted successfully');
    }
}
