<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'url', 'max:255'],
            'industry' => ['required', 'string', 'max:255'],
            'language' => ['required', 'string', 'max:255']
        ]);

        //: TODO -> CHECK IF USER HAS THE RIGHT PLAN FOR ANOTHER PROJECT

        $project = Project::create([
            'user_id' => $request->user()->id,
            'name' => $request->name,
            'url' => $request->url,
            'industry' => $request->industry,
            'language' => $request->language
        ]);

        return response()->json([
            'id' => $project->id,
            'name' => $request->name,
            'url' => $request->url,
            'industry' => $request->industry,
            'language' => $request->language
        ]);
    }
}
