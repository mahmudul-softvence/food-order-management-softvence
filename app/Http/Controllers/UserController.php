<?php

namespace App\Http\Controllers;

use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query();

        if ($request->search) {
            $users->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('phone', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->role && $request->role != 'all') {
            $users->role($request->role);
        }

        $users = $users->with(['roles'])->latest()->paginate(10)->appends($request->all());

        return view('backend.users.index', compact('users'));
    }


    public function create()
    {
        $teams = Team::get();
        return view('backend.users.create', compact('teams'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|unique:users,phone',
            'role'      => 'required|in:employee,admin,vendor',
            'password'  => 'required|min:6|confirmed',
            'employee_number' => 'required_if:role,employee,admin|nullable',
            'team_id'         => 'required_if:role,employee,admin|nullable|exists:teams,id',
            'floor'           => 'required_if:role,employee,admin|nullable',
            'row'             => 'required_if:role,employee,admin|nullable',
            'seat_number'     => 'required_if:role,employee,admin|nullable',
        ]);

        $user = User::create([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),

            'employee_number' => $request->role != 'vendor' ? $request->employee_number : null,
            'team_id'         => $request->role != 'vendor' ? $request->team_id : null,
            'floor'           => $request->role != 'vendor' ? $request->floor : null,
            'row'             => $request->role != 'vendor' ? $request->row : null,
            'seat_number'     => $request->role != 'vendor' ? $request->seat_number : null,
        ]);

        $user->assignRole($request->role);

        return redirect()->route('users')->with('success', 'User Created Successfully');
    }



    public function edit($id)
    {
        $user = User::findOrFail($id);
        $teams = Team::all();

        return view('backend.users.edit', compact('user', 'teams'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|unique:users,phone,' . $user->id,
            'role'      => 'required|in:employee,admin,vendor',
            'password'  => 'nullable|min:6|confirmed',

            'employee_number' => 'required_if:role,employee,admin|nullable',
            'team_id'         => 'required_if:role,employee,admin|nullable|exists:teams,id',
            'floor'           => 'required_if:role,employee,admin|nullable',
            'row'             => 'required_if:role,employee,admin|nullable',
            'seat_number'     => 'required_if:role,employee,admin|nullable',
        ]);

        $user->update([
            'name'      => $request->name,
            'phone'     => $request->phone,
            'password'  => $request->password ? Hash::make($request->password) : $user->password,

            'employee_number' => $request->role != 'vendor' ? $request->employee_number : null,
            'team_id'         => $request->role != 'vendor' ? $request->team_id : null,
            'floor'           => $request->role != 'vendor' ? $request->floor : null,
            'row'             => $request->role != 'vendor' ? $request->row : null,
            'seat_number'     => $request->role != 'vendor' ? $request->seat_number : null,
        ]);

        $user->syncRoles($request->role);

        return redirect()->route('users')->with('success', 'User Updated Successfully');
    }


    public function changeStatus($id)
    {
        if (!auth()->user()->hasRole('admin')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $user = User::findOrFail($id);
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return response()->json(['message' => 'Status Updated']);
    }

    public function show($id)
    {
        try {
            $user = User::with('roles', 'team')->findOrFail($id);

            return response()->json([
                'name' => $user->name,
                'phone' => $user->phone,
                'role' => $user->getRoleNames()->first(),
                'team' => optional($user->team)->name,
                'employee_number' => $user->employee_number,
                'floor' => $user->floor,
                'row' => $user->row,
                'seat_number' => $user->seat_number,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
