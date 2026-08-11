<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.form', ['user' => new User()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,manager,cashier,receptionist,employee',
            'is_active' => 'boolean',
        ]);
        $data['password'] = Hash::make($data['password']);
        $data['is_active'] = $request->boolean('is_active', true);
        $user = User::create($data);
        AuditLog::log('user_created', 'User', $user->id, $user->name);
        return redirect()->route('users.index')->with('success', __('m.success'));
    }

    public function edit(User $user)
    {
        return view('users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role' => 'required|in:admin,manager,cashier,receptionist,employee',
            'is_active' => 'boolean',
        ]);
        if ($data['password']) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        $data['is_active'] = $request->boolean('is_active', true);
        $user->update($data);
        AuditLog::log('user_updated', 'User', $user->id, $user->name);
        return redirect()->route('users.index')->with('success', __('m.success'));
    }

    public function destroy(User $user)
    {
        $user->update(['is_active' => false]);
        AuditLog::log('user_deactivated', 'User', $user->id, $user->name);
        return redirect()->route('users.index')->with('success', __('m.success'));
    }
}
