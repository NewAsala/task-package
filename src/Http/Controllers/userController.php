<?php

namespace Akrad\Bridage\Http\Controllers;

use Akrad\Bridage\Models\Assigne;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class userController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function createAssigne()
    {
        $roles = Role::all();

        return view('bridage::assigne')->with('roles',$roles);
    }

    public function storeAssigneeAndUpdate(Request $request)
    {
        if($request['name'] == null){

            return redirect(route('createAssigne')); 

        }else{

            $assignes = Assigne::all();
    
            foreach($assignes as $assigne){
                $assigne->delete();
            }
    
            foreach($request['name'] as $nameRole)
            {
                $as =  new Assigne();
    
                $as->name = $nameRole;
    
                $as->save();
            }
            return redirect(route('createAssigne')); 

        }
        // foreach($request['name'] as $nameRole){
        //     foreach($assignes as $assigne)
        //     {
        //         if($assigne->name == $nameRole)
        //         {
        //             $ass = Assigne::find($assigne->id);
        
        //             $ass->name = $nameRole;

        //             $ass->save();
        //             return redirect(route('createAssigne'));
        //         }
                
        //     }
        //     $as =  new Assigne();

        //     $as->name = $nameRole;

        //     $as->save();

        // }
            
    }

    public function getAllAssignee()
    {
        $roles = Role::all();
        $assignes = Assigne::all();

        if($assignes->isNotEmpty()){
        
            foreach($roles as $role){
                foreach($assignes as $assigne)
                {
                    if($role->name == $assigne->name)
                    {
                        $result [] = $role->users->all();
                    }
                }
            }
            return $result;
        }else{
            return response()->json("Enter The Role To Get User Assignee");
        }
    }

}


