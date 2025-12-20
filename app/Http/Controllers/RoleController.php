<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::get();
        return view('backend.roles.index', compact('roles'));
    }

    public function create()
    {
        $permissions = Permission::all()->map(function ($p) {
            $p->group = explode('.', $p->name)[0];
            return $p;
        });


        return view('backend.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create([
            'name' => $request->name,
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles')->with('success', 'Role created successfully!');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);

        $permissions = Permission::all()->map(function ($p) {
            $p->group = explode('.', $p->name)[0];
            return $p;
        });

        return view('backend.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);
        $role->name = $request->name;
        $role->save();

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles')->with('success', 'Role updated successfully');
    }


    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if (in_array($role->name, ['admin', 'super-admin'])) {
            return redirect()->route('roles')->with('error', 'This role cannot be deleted.');
        }

        $role->delete();

        return redirect()->route('roles')->with('success', 'Role deleted successfully!');
    }
}
