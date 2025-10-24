<?php

namespace App\Http\Controllers;

use App\Models\Laptop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaptopBackendController extends Controller
{
    // Tampilkan semua data laptop
    public function index()
    {

        $laptops = Laptop::all();
        return view('pages.laptop.index', compact('laptops'));
    }

    // Tampilkan form tambah laptop
    public function create()
    {
        return view('pages.laptop.create');
    }

    // Simpan data laptop baru
    public function store(Request $request)
{
    $request->validate([
        'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp,jfif|max:4096',
        'brand' => 'required|string|max:255',
        'model' => 'required|string|max:255',
        'release_year' => 'nullable|digits:4|integer|min:1970|max:' . (date('Y') + 1),
    ]);

    // Cek apakah kombinasi brand + model + release_year sudah ada
    $exists = Laptop::where('brand', $request->brand)
        ->where('model', $request->model)
        ->where('release_year', $request->release_year)
        ->exists();

    if ($exists) {
        // Kirimkan session error untuk ditangkap di Blade
        return redirect()->back()
            ->withInput()
            ->with('duplicate_error', 'Laptop dengan brand, model, dan tahun rilis yang sama sudah ada.');
    }

    $data = [
        'brand' => $request->brand,
        'model' => $request->model,
        'release_year' => $request->release_year,
        'is_active' => 'active',
    ];

    if ($request->hasFile('foto')) {
        $data['image'] = $request->file('foto')->store('laptops', 'public');
    }

    Laptop::create($data);

    return redirect('/laptop')->with('success', 'Laptop berhasil ditambahkan');
}


    // Tampilkan form edit laptop
    public function edit($id)
    {
        $laptop = Laptop::find($id);

        if (!$laptop) {
            return redirect('/laptop')->with('error', 'Laptop tidak ditemukan');
        }

        return view('pages.laptop.edit', compact('laptop'));
    }

    // Update data laptop
    public function update(Request $request, $id)
    {
        $laptop = Laptop::find($id);

        if (!$laptop) {
            return redirect('/laptop')->with('error', 'Laptop tidak ditemukan');
        }

        $request->validate([
             'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp,jfif|max:4096',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'release_year' => 'nullable|digits:4|integer|min:1970|max:' . (date('Y') + 1),
        ]);

        $dataUpdate = [
            'brand' => $request->brand,
            'model' => $request->model,
            'release_year' => $request->release_year,
        ];

        if ($request->hasFile('foto')) {
            if ($laptop->image) {
                Storage::disk('public')->delete($laptop->image);
            }
            $dataUpdate['image'] = $request->file('foto')->store('laptops', 'public');
        }

        $laptop->update($dataUpdate);

        return redirect('/laptop')->with('success', 'Laptop berhasil diupdate');
    }

    // Hapus laptop
   public function destroy($id)
{
    $laptop = Laptop::find($id);

    if ($laptop) {
        // Jangan hapus file fisik dulu
        $laptop->delete(); // 🟢 ini soft delete
        return redirect('/laptop')->with('success', 'Laptop berhasil dihapus (soft delete)');
    }

    return redirect('/laptop')->with('error', 'Laptop tidak ditemukan');
}

public function restore($id)
{
    $laptop = Laptop::onlyTrashed()->find($id);
    if ($laptop) {
        $laptop->restore();
        return redirect('/laptop')->with('success', 'Laptop berhasil dikembalikan');
    }
    return redirect('/laptop')->with('error', 'Laptop tidak ditemukan');
}


    // Toggle status aktif/nonaktif
    public function toggle(Request $request, $id)
    {
        $laptop = Laptop::findOrFail($id);
        $laptop->is_active = $request->status == 1 ? 'active' : 'nonactive';
        $laptop->save();

        return response()->json([
            'success' => true,
            'is_active' => $laptop->is_active
        ]);
    }
}
