<?php

namespace Akrad\Bridage\Http\Controllers;

use Akrad\Bridage\Events\DeleteProject;
use Akrad\Bridage\Events\Project;
use Akrad\Bridage\Events\UpdateProject;
use Akrad\Bridage\Models\Action;
use Akrad\Bridage\Models\Helper;
use Akrad\Bridage\Models\Models;
use Akrad\Bridage\Models\Operation;
use Akrad\Bridage\Observers\createObserver;
use Akrad\Bridage\Observers\UserObserver;
use Akrad\Bridage\Providers\TestServiceProvider;
use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Builder;
use PhpOption\None;
use Str;


class helperController extends Controller
{
    public static $conditionToFormat ="";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function create()
    {   
        $models = $this->getAllModels();
        
        $actions = Action::all();

        return view('bridage::listener')->with('models', $models)->with('actions',$actions);
    }

    public function processOperation($operation , $user_input,$attribute)
    {
        
    }

    public function formatCondition($listOfConditions)
    {
        $conditions = json_decode($listOfConditions);

        $prefix1 = $conditions->prefix;
        $suffix1 = $conditions->suffix;

        if((!property_exists($conditions, 'combined_condition_left_side')) || (!property_exists($conditions, 'combined_condition_right_side'))){

            $prefix = $conditions->prefix;
            $attribute  = $conditions->attribute;
            $operation = $conditions->operator;
            $user_input = $conditions->user_input;
            $suffix = $conditions->suffix;

            if(($operation == 'like') || ($operation == 'in'))
            {
                if(Str::contains($suffix,"NONE"))
                {
                    $suffix = str_replace($suffix,' ',$suffix);
                }
                static::$conditionToFormat = ' '.$prefix ." Str::contains(".'{{$'.$attribute.'}}'.",$user_input)".$suffix.' ';
            }else{
                
                static::$conditionToFormat = ' '.$prefix .' {{$'.$attribute.'}} '.$operation. ' '.$user_input.' '.$suffix.' ';
            }
            return static::$conditionToFormat;

        }else{
            
            return $prefix1.$this->formatCondition(json_encode($conditions->combined_condition_left_side)).$conditions->combined_condition_operator.$this->formatCondition(json_encode($conditions->combined_condition_right_side)).$suffix1;
        }
    }

    public function send(Request $request)
    {
        $temp =$request->complex_condition_data;

        $condition = json_encode($this->formatCondition($temp));
        dd($condition);
        
        $actions = json_encode($request->action_data);

        $helper =  new Helper();
        
        if(Str::contains($request->condition_type, 'complex')){
       
            $temp =$request->complex_condition_data;
            $condition = json_encode($this->formatCondition($temp));
        }else{
            $condition = json_encode($request->simple_condition_data);
        }

        $helper->events = $request->events;
        $helper->object = $request->models;
        $helper->task_name = $request->task;
        $helper->group_name = $request->group;
        $helper->user = $request->users;
        $helper->action = $actions;//json_encode($request['actions']);
        $helper->attribute = json_encode($request['attribute']); // to be deleted
        $helper->conditionRule = $condition;
        $helper->statuse = $request->status;

        $helper->save();

        return redirect()->back()->with('success', 'The Rule is successfully Created ');
    }

    /*public function send(Request $request)
    {
        //dd(json_decode($request->complex_condition_data)[0]->attribute);
        $allHelper = Helper::all();

        foreach ($allHelper as $helper) {
            if ($request->events ==  $helper->events && $request->models == $helper->object) {
                $helper = Helper::find($helper->id);

                $this->save($helper, $request);

                return redirect(route('getModels'));
            }
        }

        $helper =  new Helper();

        $this->save($helper, $request);

        return redirect(route('getModels'));
    }

    public function save($helper, $request)
    {
        $helper->events = $request->events ? $request->events : $helper->events;
        $helper->object = $request->models ? $request->models : $helper->object;
        $helper->task_name = $request->task ? $request->task : $helper->task_name;
        $helper->group_name = $request->group ? $request->group : $helper->group_name;
        $helper->user = $request->users ? $request->users : $helper->user;
        $helper->statuse = $request->statuse;

        if ($request['actions'] == null) {
            $helper->action = $helper->action;
        } else {
            $helper->action = json_encode($request['actions']) ? json_encode($request['actions']) : $helper->action;
        }

        if ($request['attribute'] == null) {
            $helper->attribute = $helper->attribute;
        } else {
            // foreach($request['attribute'] as $att)
            // {
            //     $attribute [] = '{{$'.$att.'}}';
            // }
            $helper->attribute = json_encode($request['attribute']);
        }

        $helper->save();
    }*/
 
    function getAllModels()
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $models = [];

        $data = (array)data_get($composer, 'autoload.psr-4');
        foreach ($data as $namespace => $path) {
            $collected = collect(File::allFiles(base_path($path)));
            $mapped = $collected->map(function ($item) use ($namespace) {
                $path = $item->getRelativePathName();

                return sprintf(
                    '\%s%s',
                    $namespace,
                    strtr(substr($path, 0, strrpos($path, '.')), '/', '\\')
                );
            });
            $filtered =  $mapped->filter(function ($class) {
                $valid = false;
                    if (class_exists($class)) {
                        $reflection = new \ReflectionClass($class);
                        $valid = $reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class) &&
                            !$reflection->isAbstract();
                    }
                return $valid;
            });
            $models = array_merge(
                $filtered
                    ->values()
                    ->toArray(),
                $models
            );
        }
        return $models;
    }
    
    public function getAttribute(Request $request)
    {
        //$path = ucwords(str_replace('_', '\\', $value));
        $model = Models::makeModel($request->path);
        $tableNmae = $model->getTable();
        $columnsWithType = DB::select('describe '.$tableNmae);

        foreach($columnsWithType as  $column){
        
            $operation [$column->Field] = $this->Operation($column->Type);

        }
        return $operation;
    }

    public function Operation($type)
    {
        $operation = Operation::getKeys();

        if(Str::contains($type, 'int')){

            return $operation['operatorForInteger'];
        
        }else if(Str::contains($type, 'text') || Str::contains($type, 'string') || Str::contains($type, 'varchar')){

            return $operation['operatorForString'];

        }else if (Str::contains($type, 'timestamp')){

            return $operation['operatorForDate'];

        }else{  // json , enum

            return $operation['other'];
        }
    }

}
