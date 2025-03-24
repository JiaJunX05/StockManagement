<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Zone;

class ZoneController extends Controller
{
    public function index() {
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

        return redirect()->route('zone.list')->with('success', 'Zone created successfully');
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

        return redirect()->route('zone.list')->with('success', 'Zone updated successfully');
    }

    public function destroy($id) {
        $zones = Zone::findOrFail($id);

        if ($zones->storacks()->exists()) {
            return redirect()->route('zone.list')->withErrors(['error' => 'Cannot delete this zone because storacks are still linked to it.']);
        }

        $zones->delete();

        return redirect()->route('zone.list')->with('success', 'Zone deleted successfully');
    }
}
