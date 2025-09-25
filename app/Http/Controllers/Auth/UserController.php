<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        // Only allow admins & super admin to manage users
        $this->middleware('role:super admin|admin');
    }

    public function index()
    {
        $users = User::paginate(15);
        return view('surgeries.users.index', compact('users'));
    }

    public function create()
    {
        return view('surgeries.users.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name'=>'required|string',
            'email'=>'required|email|unique:users',
            'password'=>['required','confirmed', Password::min(8)],
            'role'=>'required|in:super admin,admin,user'
        ]);

        User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>Hash::make($data['password']),
            'role'=>$data['role'],
            'email_verified_at'=>now()
        ]);

        return redirect()->route('users.index')->with('success','User created.');
    }

    public function edit(User $user)
    {
        return view('surgeries.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name'=>'required|string',
            'email'=>"required|email|unique:users,email,{$user->id}",
            'password'=>'nullable|confirmed|min:8',
            'role'=>'required|in:super admin,admin,user'
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->role = $data['role'];
        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }
        $user->save();

        return redirect()->route('users.index')->with('success','User updated.');
    }

    public function destroy(User $user)
    {
        // prevent deleting self or last super admin? implement as needed
        $user->delete();
        return back()->with('success', 'User deleted.');
    }
}
