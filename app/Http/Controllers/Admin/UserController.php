<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Daftar pengguna (admin / teknisi / user) + pencarian live
    public function index(Request $request)
    {
        $users = User::withCount(['tickets', 'assignedTickets'])
            ->when($request->filled('q'), function ($query) use ($request) {
                $q = $request->q;
                $query->where(function ($sub) use ($q) {
                    $sub->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%");
                });
            })
            ->when($request->filled('role') && in_array($request->role, ['admin', 'technician', 'user']), function ($query) use ($request) {
                $query->where('role', $request->role);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        if ($request->boolean('ajax')) {
            return response()->json([
                'data' => $users->getCollection()->map(fn($u) => [
                    'id' => $u->id,
                    'name' => $u->name,
                    'email' => $u->email,
                    'role' => $u->role,
                    'telegram_linked' => !is_null($u->telegram_chat_id),
                    'tickets_count' => $u->tickets_count,
                    'assigned_count' => $u->assignedTickets_count,
                    'created_at' => $u->created_at->format('d M Y, H:i'),
                    'is_self' => $u->id === auth()->id(),
                    'edit_url' => route('admin.users.edit', $u->id),
                    'destroy_url' => route('admin.users.destroy', $u->id),
                ])->values(),
                'pagination' => $users->links()->toHtml(),
                'total' => $users->total(),
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
            ]);
        }

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,technician,user',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$user->name} ({$user->role}) berhasil ditambahkan.");
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,technician,user',
        ]);

        // Proteksi: tidak boleh mencabut role admin diri sendiri
        if ($user->id === auth()->id() && $validated['role'] !== 'admin') {
            return redirect()->back()->withInput()
                ->with('error', 'Anda tidak dapat mencabut hak admin dari akun sendiri.');
        }

        // Proteksi: sistem harus selalu punya minimal 1 admin
        if ($user->role === 'admin' && $validated['role'] !== 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->back()->withInput()
                ->with('error', 'Tidak dapat mengubah role: sistem harus memiliki minimal 1 admin.');
        }

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
        ]);

        if (!empty($validated['password'])) {
            $user->update(['password' => Hash::make($validated['password'])]);
        }

        return redirect()->route('admin.users.index')
            ->with('success', "Data pengguna {$user->name} berhasil diperbarui.");
    }

    public function destroy($id)
    {
        $user = User::withCount(['tickets', 'assignedTickets'])->findOrFail($id);

        // Proteksi: tidak boleh menghapus akun sendiri
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        // Proteksi: admin terakhir tidak boleh dihapus
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus: sistem harus memiliki minimal 1 admin.');
        }

        // Proteksi: user yang masih punya riwayat tiket tidak boleh dihapus
        // (relasi tickets memakai onDelete cascade, jadi hapus di sini = tiket ikut hilang)
        if ($user->tickets_count > 0 || $user->assignedTickets_count > 0) {
            return redirect()->back()->with(
                'error',
                "Tidak dapat menghapus {$user->name}: masih memiliki {$user->tickets_count} tiket laporan dan {$user->assignedTickets_count} tiket penanganan."
            );
        }

        $name = $user->name;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "Pengguna {$name} berhasil dihapus.");
    }
}
