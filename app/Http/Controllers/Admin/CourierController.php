<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Courier;
use Illuminate\Http\Request;

class CourierController extends Controller
{
    public function index()
    {
        $couriers = Courier::latest()->get();
        // Mengarah ke folder 'courier' (tanpa S)
        return view('admin.pages.courier.index', compact('couriers'));
    }

    public function create()
    {
        // Mengarah ke folder 'courier' (tanpa S)
        return view('admin.pages.courier.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:255',
        ]);

        Courier::create([
            'name' => $request->name,
            'phone' => $request->phone,
            'vehicle_number' => $request->vehicle_number,
            'is_active' => $request->has('is_active'),
        ]);

        // Redirect tetap pakai 'couriers' (pakai S) karena bawaan Route::resource
        return redirect()->route('admin.couriers.index')->with('success', 'Data armada/kurir berhasil ditambahkan!');
    }

    public function edit(Courier $courier)
    {
        // Mengarah ke folder 'courier' (tanpa S)
        return view('admin.pages.courier.edit', compact('courier'));
    }

    public function update(Request $request, Courier $courier)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:255',
        ]);

        $courier->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'vehicle_number' => $request->vehicle_number,
            'is_active' => $request->has('is_active'),
        ]);

        // Redirect tetap pakai 'couriers' (pakai S)
        return redirect()->route('admin.couriers.index')->with('success', 'Data armada/kurir berhasil diperbarui!');
    }

    public function destroy(Courier $courier)
    {
        $courier->delete();
        // Redirect tetap pakai 'couriers' (pakai S)
        return redirect()->route('admin.couriers.index')->with('success', 'Data armada/kurir berhasil dihapus!');
    }
}   