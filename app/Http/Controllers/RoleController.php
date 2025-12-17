<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
        return view('backend.roles.create');
    }

    public function edit()
    {
        return view('backend.roles.edit');
    }
}
