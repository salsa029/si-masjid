<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $users = User::query()
            ->with('roles')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('role'), fn ($query) => $query->role($request->input('role')))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $totalUsers = User::count();
        $totalAdmins = User::role('admin')->count();
        $totalJamaah = User::role('jamaah')->count();

        $roles = Role::pluck('name');

        return view('admin.users.index', compact('users', 'totalUsers', 'totalAdmins', 'totalJamaah', 'roles'));
    }

    public function create(): View
    {
        $roles = Role::pluck('name');

        return view('admin.users.create', compact('roles'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'email_verified_at' => now(),
        ]);

        $user->assignRole($validated['role']);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $roles = Role::pluck('name');

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        if (
            $user->hasRole('admin')
            && $validated['role'] !== 'admin'
            && User::role('admin')->count() <= 1
        ) {
            return back()->with('error', 'Tidak bisa mengubah role admin terakhir yang tersisa.');
        }

        $user->name = $validated['name'];
        $user->email = $validated['email'];

        if (! empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();
        $user->syncRoles([$validated['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        if ($user->hasRole('admin') && User::role('admin')->count() <= 1) {
            return back()->with('error', 'Tidak bisa menghapus admin terakhir yang tersisa.');
        }

        $user->delete();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
