<?php

namespace App\Http\Controllers;

use App\Models\Serviceitem;
use Illuminate\Http\Request;

class ServiceitemBackendController extends Controller
{
    // Tampilkan semua service item
    public function index()
    {
        $serviceitems = Serviceitem::all();
        return view('pages.serviceitem.index', compact('serviceitems'));
    }

    // Form tambah service item
    public function create()
    {
        return view('pages.serviceitem.create');
    }

    // Simpan service item baru
   public function store(Request $request)
{
    $request->validate([
        'service_name' => 'required|string|max:255',
        'price' => 'required|numeric|min:0',
    ]);

    // 🔍 Cek apakah nama service sudah ada
    $exists = Serviceitem::where('service_name', $request->service_name)->exists();

    if ($exists) {
        // kirim session error ke blade
        return redirect()->back()
            ->withInput()
            ->with('duplicate_error', 'Nama service ini sudah ada di database.');
    }

    Serviceitem::create([
        'service_name' => $request->service_name,
        'price' => $request->price,
        'is_active' => 'active',
    ]);

    return redirect('/serviceitem')->with('success', 'Service Item berhasil ditambahkan');
}


    // Form edit service item
    public function edit($id)
    {
        $serviceitem = Serviceitem::find($id);

        if (!$serviceitem) {
            return redirect('/serviceitem')->with('error', 'Service Item tidak ditemukan');
        }

        return view('pages.serviceitem.edit', compact('serviceitem'));
    }

    // Update service item
    public function update(Request $request, $id)
    {
        $serviceitem = Serviceitem::find($id);

        if (!$serviceitem) {
            return redirect('/serviceitem')->with('error', 'Service Item tidak ditemukan');
        }

        $request->validate([
            'service_name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $serviceitem->update([
            'service_name' => $request->service_name,
            'price' => $request->price,
        ]);

        return redirect('/serviceitem')->with('success', 'Service Item berhasil diupdate');
    }

    // Hapus service item
    // Hapus service item (soft delete)
public function destroy($id)
{
    $serviceitem = Serviceitem::find($id);

    if ($serviceitem) {
        $serviceitem->delete(); // ini soft delete, bukan hard delete
        return redirect('/serviceitem')->with('success', 'Service Item berhasil dihapus (soft delete)');
    }

    return redirect('/serviceitem')->with('error', 'Service Item tidak ditemukan');
}
public function restore($id)
{
    $serviceitem = Serviceitem::withTrashed()->find($id);

    if ($serviceitem && $serviceitem->trashed()) {
        $serviceitem->restore();
        return redirect('/serviceitem')->with('success', 'Service Item berhasil direstore!');
    }

    return redirect('/serviceitem')->with('error', 'Data tidak ditemukan atau belum dihapus.');
}


    // Toggle status aktif/nonaktif
    public function toggle(Request $request, $id)
    {
        $serviceitem = Serviceitem::findOrFail($id);
        $serviceitem->is_active = $request->status == 1 ? 'active' : 'nonactive';
        $serviceitem->save();

        return response()->json([
            'success' => true,
            'is_active' => $serviceitem->is_active
        ]);
    }
}
