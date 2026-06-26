<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        return view('owner.users.index', [
            'users' => User::orderBy('role')->orderBy('name')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('owner.users.form', ['user' => new User]);
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);
        $data['password'] = Hash::make($data['password']);

        User::create($data);

        return redirect()->route('owner.users.index')->with('status', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        return view('owner.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $this->validateData($request, $user->id);

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('owner.users.index')->with('status', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('status', 'User berhasil dihapus.');
    }

    private function validateData(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($ignoreId)],
            'role' => ['required', Rule::in(UserRole::values())],
            'phone' => ['nullable', 'string', 'max:30'],
            'password' => [$ignoreId ? 'nullable' : 'required', 'nullable', 'string', 'min:8'],
        ]);

        $data['is_active'] = $request->boolean('is_active');

        return $data;
    }
}
