<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function create()
    {
        return view('projects.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);

        Project::create([
            'name' => $request->title,
            'description' => $request->description,
            'client_id' => 1
        ]);

        return redirect('/projects')->with('success', 'Projet créé');
    }
}
