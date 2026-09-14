<?php

namespace App\Http\Controllers;

use App\Models\ImpactFactor;
use Illuminate\Http\Request;

class ImpactFactorController extends Controller
{
    public function index()
    {
        $factors = ImpactFactor::orderBy('material_type')->get();
        return view('admin.impact_factors', compact('factors'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'material_type' => ['required', 'string', 'max:100', 'unique:impact_factors,material_type'],
            'waste_per_item' => ['required', 'numeric', 'min:0', 'max:9999'],
            'carbon_per_item' => ['required', 'numeric', 'min:0', 'max:9999'],
        ]);

        ImpactFactor::create($data);

        return back()->with('success', 'Material dampak ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $factor = ImpactFactor::findOrFail($id);

        $data = $request->validate([
            'material_type' => ['required', 'string', 'max:100', 'unique:impact_factors,material_type,'.$factor->id],
            'waste_per_item' => ['required', 'numeric', 'min:0', 'max:9999'],
            'carbon_per_item' => ['required', 'numeric', 'min:0', 'max:9999'],
        ]);

        $factor->update($data);

        return back()->with('success', 'Material dampak diperbarui.');
    }

    public function destroy($id)
    {
        ImpactFactor::findOrFail($id)->delete();
        return back()->with('success', 'Material dampak dihapus.');
    }
}
