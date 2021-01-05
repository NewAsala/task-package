<?php

namespace Akrad\Bridage\Http\Controllers;

use Akrad\Bridage\Models\Action;
use Akrad\Bridage\Models\Assigne;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class ActionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create ()
    {
        return view('bridage::dynamicFunction');
    }

    public function store(Request $request)
    {
        $allaction = Action::all();

        foreach ($allaction as $action) {
            
            if ($request->func ==  $action->name) {
                $action = Action::find($action->id);

                $this->save($action, $request);

                return redirect(route('getfunc'));
            }
        }

        $action =  new Action();

        $this->save($action, $request);

        return redirect(route('getfunc'));
    }

    public function save($action, $request)
    {
        $parameter = json_encode($request->parameter);

        $action->name = $request->func?$request->func:$action->name;
        $action->parameter = $parameter?$parameter:$action->parameter;
        $action->returnFun = $request->returnFun?$request->returnFun:$action->returnFun;

        $action->save();
    }

    /*public function store (Request $request)
    {
        $parameter = json_encode($request->parameter);

        $action = new Action();
        $action->name = $request->func;
        $action->parameter = $parameter;
        $action->returnFun = $request->returnFun;

        $action->save();

        return redirect(route('getfunc'));
    }*/

    //test
    public function edit ()
    {
        return view('bridage::editdynamicFunction');
    }

    public function update (Request $request,Action $action)
    {
        $parameter = json_encode($request->parameter);

        $action = Action::find($action->id);

        $action->name = $request->func?$request->func:$action->name;
        $action->parameter = $parameter?$parameter:$action->parameter;

        $action->save();

        return redirect(route('getfunc'));
    }
    ///

    public function getParameter(Request $request)
    {
        $actions = Action::all();

        foreach($actions as $action)
        {
            if($action->name == $request->functionName)
            {
                $functionWithParameter [$action->name] []= json_decode($action->parameter);
            }
        }
        return $functionWithParameter;
    }

}


