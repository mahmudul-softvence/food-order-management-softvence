<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index(Request $request)
    {
        $teams = Team::when($request->search, function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%');
        })->with(['users'])->latest()->paginate(10);

        return view('backend.teams.index', compact('teams'));
    }


    public function create()
    {
        return view('backend.teams.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|unique:teams,name',
            'status' => 'required|in:0,1',
            'note'   => 'nullable|string|max:255',
        ]);

        Team::create($request->only('name', 'status', 'note'));

        return redirect()->route('teams')->with('success', 'Team created successfully');
    }

    public function edit($id)
    {
        $team = Team::find($id);

        return view('backend.teams.edit', compact('team'));
    }

    public function update(Request $request, $id)
    {
        $team = Team::find($id);

        $request->validate([
            'name'   => 'required|unique:teams,name,' . $team->id . ',id',
            'status' => 'required|in:0,1',
            'note'   => 'nullable|string|max:255',
        ]);

        $team->update($request->only('name', 'status', 'note'));

        return redirect()->route('teams')->with('success', 'Team updated successfully');
    }

    public function destroy($id)
    {
        $team = Team::find($id);

        $team->delete();

        return redirect()->route('teams')->with('success', 'Team deleted successfully');
    }
}
