<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Usertiga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserBackendController extends Controller
{
   public function index()
    {
        $users = Usertiga::all();
        return view('pages.user.index', compact('users'));
    }

    public function create()
    {
        return view('pages.user.create');
    }

    public function store(Request $request)
    {


        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp,jfif|max:4096',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phonenumber' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:admin,customer,technician',
        ]);

        $data = [
            'name' => $request->name,
            'address' => $request->address,
            'phonenumber' => $request->phonenumber,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'is_active' => 'active',
        ];

        if ($request->hasFile('foto')) {
            $data['image'] = $request->file('foto')->store('users', 'public');
        }

        Usertiga::create($data);

        return redirect('/user')->with('success', 'Data Berhasil Ditambahkan');
    }

    public function edit($id)
    {
        $user = Usertiga::find($id);

        if (!$user) {
            return redirect('/user')->with('error', 'Data Tidak Ditemukan');
        }

        return view('pages.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = Usertiga::find($id);

        if (!$user) {
            return redirect('/user')->with('error', 'Data Tidak Ditemukan');
        }

        $request->validate([
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp,jfif|max:4096',
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phonenumber' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,customer,technician',
        ]);

        $dataUpdate = [
            'name' => $request->name,
            'address' => $request->address,
            'phonenumber' => $request->phonenumber,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->hasFile('foto')) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $dataUpdate['image'] = $request->file('foto')->store('users', 'public');
        }

        $user->update($dataUpdate);

        return redirect('/user')->with('success', 'Data Berhasil Diupdate');
    }

    public function destroy($id)
    {
        $user = Usertiga::find($id);

        if ($user) {
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }
            $user->delete();
            return redirect('/user')->with('success', 'Data Berhasil Dihapus');
        }

        return redirect('/user')->with('error', 'Data Tidak Ditemukan');
    }
    public function show($id)
{
    $user = Usertiga::find($id);

    if (!$user) {
        return redirect('/user')->with('error', 'Data Tidak Ditemukan');
    }

    return view('pages.user.show', compact('user'));
}

    public function toggle(Request $request, $id)
    {
        $user = Usertiga::findOrFail($id);
        $user->is_active = $request->status == 1 ? 'active' : 'nonactive';
        $user->save();

        return response()->json([
            'success'   => true,
            'is_active' => $user->is_active
        ]);
    }
}
