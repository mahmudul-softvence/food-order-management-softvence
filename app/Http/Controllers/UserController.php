<?php

namespace App\Http\Controllers;

use App\Helpers\UploadHelper;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

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
        $roles = Role::get();
        return view('backend.users.create', compact('teams', 'roles'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|unique:users,phone',
            'role'      => 'required',
            'password'  => 'required|min:8|confirmed',

            'employee_number' => 'required_unless:role,employee,admin|nullable',
            'team_id'         => 'required_unless:role,employee,admin|nullable|exists:teams,id',
            'floor'           => 'required_unless:role,employee,admin|nullable',
            'row'             => 'required_unless:role,employee,admin|nullable',
            'seat_number'     => 'required_unless:role,employee,admin|nullable',

            'nid'             => 'required_if:role,vendor|nullable',
            'nid_image'       => 'required_if:role,vendor|nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
            'trade_licence'   => 'required_if:role,vendor|nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
            'visiting_card'   => 'required_if:role,vendor|nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
        ]);

        $userData = [
            'name'      => $request->name,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
        ];

        if ($request->role != 'vendor') {
            $userData['employee_number'] = $request->employee_number;
            $userData['team_id']         = $request->team_id;
            $userData['floor']           = $request->floor;
            $userData['row']             = $request->row;
            $userData['seat_number']     = $request->seat_number;
        }

        $user = User::create($userData);

        if ($request->role == 'vendor') {
            $user->nid_image = UploadHelper::handleUpload($request->file('nid_image'), 'uploads/nid_images/');
            $user->trade_licence = UploadHelper::handleUpload($request->file('trade_licence'), 'uploads/trade_licences/');
            $user->visiting_card = UploadHelper::handleUpload($request->file('visiting_card'), 'uploads/visiting_cards/');
            $user->nid = $request->nid;

            $user->save();
        }

        $user->assignRole($request->role);

        return redirect()->route('users')->with('success', 'User Created Successfully');
    }




    public function edit($id)
    {
        $user = User::findOrFail($id);
        $teams = Team::all();
        $roles = Role::get();

        return view('backend.users.edit', compact('user', 'teams', 'roles'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'      => 'required|string|max:255',
            'phone'     => 'required|unique:users,phone,' . $user->id,
            'role'      => 'required',
            'password'  => 'nullable|min:8|confirmed',

            'employee_number' => 'required_unless:role,employee,admin|nullable',
            'team_id'         => 'required_unless:role,employee,admin|nullable|exists:teams,id',
            'floor'           => 'required_unless:role,employee,admin|nullable',
            'row'             => 'required_unless:role,employee,admin|nullable',
            'seat_number'     => 'required_unless:role,employee,admin|nullable',

            'nid'             => 'required_if:role,vendor|nullable',
            'nid_image'       => 'required_if:role,vendor|nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
            'trade_licence'   => 'required_if:role,vendor|nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
            'visiting_card'   => 'required_if:role,vendor|nullable|file|mimes:jpeg,png,jpg,pdf|max:10240',
        ]);

        $userData = [
            'name'      => $request->name,
            'phone'     => $request->phone,
            'role'      => $request->role,
            'password'  => $request->password ? Hash::make($request->password) : $user->password,
        ];

        if ($request->role != 'vendor') {
            $userData['employee_number'] = $request->employee_number;
            $userData['team_id']         = $request->team_id;
            $userData['floor']           = $request->floor;
            $userData['row']             = $request->row;
            $userData['seat_number']     = $request->seat_number;
        } else {
            $userData['nid'] = $request->nid;
        }

        $user->update($userData);

        if ($request->role == 'vendor') {
            if ($request->hasFile('nid_image')) {
                if ($user->nid_image) {
                    Storage::delete($user->nid_image);
                }
                $user->nid_image = UploadHelper::handleUpload($request->file('nid_image'), 'uploads/nid_images/', $user->nid_image);
            }

            if ($request->hasFile('trade_licence')) {
                if ($user->trade_licence) {
                    Storage::delete($user->trade_licence);
                }
                $user->trade_licence = UploadHelper::handleUpload($request->file('trade_licence'), 'uploads/trade_licences/', $user->trade_licence);
            }

            if ($request->hasFile('visiting_card')) {
                if ($user->visiting_card) {
                    Storage::delete($user->visiting_card);
                }
                $user->visiting_card = UploadHelper::handleUpload($request->file('visiting_card'), 'uploads/visiting_cards/', $user->visiting_card);
            }

            $user->save();
        }

        $user->syncRoles($request->role);

        return redirect()->route('users')->with('success', 'User Updated Successfully');
    }





    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('users')->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('users')->with('success', 'User deleted successfully!');
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



    public function loginAs($id)
    {
        $user = User::findOrFail($id);

        if (!session()->has('admin_id')) {
            session(['admin_id' => Auth::id()]);
        }

        Auth::login($user);

        return redirect()->route('dashboard')->with('success', "You are now logged in as {$user->name}");
    }

    public function returnAdmin()
    {
        if (session()->has('admin_id')) {
            $adminId = session('admin_id');
            $admin = User::findOrFail($adminId);

            Auth::login($admin);
            session()->forget('admin_id');

            return redirect()->route('dashboard')->with('success', 'You have returned to your admin account');
        }

        return redirect()->route('dashboard')->with('error', 'Admin session not found');
    }
}
