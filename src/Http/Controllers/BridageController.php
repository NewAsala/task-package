<?php

namespace Akrad\Bridage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Akrad\Bridage\Events\Project;
use Akrad\Bridage\Models\addProject;
use Akrad\Bridage\Models\bridage;

class BridageController extends Controller
{
    public function index ()
    {
        return view('bridage::bridage');
    }

    public function send (Request $request)
    {
        bridage::create($request->all());
    }

    public function addProject ()
    {
        $add = addProject::first();
        event(new Project($add));
        return view('bridage::addProject')->with('add', $add);
    }
}
