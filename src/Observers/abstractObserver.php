<?php

namespace Akrad\Bridage\Observers;

use Akrad\Bridage\Models\Action;
use Akrad\Bridage\Models\Helper;
use Akrad\Bridage\Models\Models;
use Modules\TMS\Entities\Project as EntitiesProject;
use Modules\TMS\Entities\Space;
use Modules\TMS\Entities\TasksList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Str;


class abstractObserver
{
    public static function checkRule($model,$event)
    {
        $information  = Helper::where('events',$event)->get();

        foreach($information as $info){

            $modelName = Models::makeModel($info->object);
            $tableName = $modelName->getTable();
            // $modelName = explode("\\",$info->object);
            // $modelNameFormat = strtolower($modelName[count($modelName)-1]."s");
            $conditions = json_decode($info->conditionRule);

            if($model->getTable() == $tableName){
                
                if($info->statuse)
                {
                    $attributes = self::getParameter($tableName);
                    
                    $check  =self::executeCondition($attributes ,$model,$conditions);
                    if($check){

                        $arrayAction = json_decode(json_decode($info->action));
                        
                        foreach($arrayAction as $action)
                        {
                            self::executeFunction($attributes,$model,$action);
                        }
                    }
                }
            }
        }
    }
   
    public static function executeFunction($attributes,$model,$action)
    {   
        $functionName = Action::where('name',$action->functionName)->first()->name;

        if($functionName){

            foreach($action->parameters as $parameter=>$parameterValue){

                foreach($attributes as $attribute){

                    $attributeFromModel = self::getAttributFromModel($model->$attribute);

                    if(Str::contains($parameterValue, '{{$'.$attribute.'}}'))
                        $parameterValue= str_replace('{{$'.$attribute.'}}', $attributeFromModel, $parameterValue);

                    $parameterEval[$parameter]= $parameterValue;
                }
            }
            self::$functionName($parameterEval);
        }else{
            return "Must be have function implementation in conde";
        }      
    }

    public static function executeCondition($attributes,$model,$conditions)
    {
        if(is_string($conditions)){

            foreach($attributes as $attribute){

                if(Str::contains($conditions, '{{$'.$attribute.'}}')){

                    $attEval =  self::getAttributFromModel($model->$attribute);
                    $conditions= str_replace('{{$'.$attribute.'}}', $attEval, $conditions);
                }
            }
            return @eval("return $conditions;");
        }
        // }else
        // {
        //     foreach($conditions as $condition){
                                    
        //         $prefix = $condition->prefix;
        //         $attribute = $condition->attribute;
        //         $operation = $condition->operator;
        //         $user_input = $condition->user_input;
        //         $suffix = $condition->suffix;
                
        //         $attributeModel = $model->$attribute;
        //         $attEval =  self::getAttributFromModel($attributeModel);

        //         $conditionToEval = $prefix .$attEval.$operation. $user_input.$suffix;

        //         $checkCondition = @eval("return $conditionToEval;");
        //     }
        //     return $checkCondition;
        // }
    }

    public static function getParameter ($tableName)
    {
        $columnsWithType = DB::select('describe '.$tableName);

        foreach($columnsWithType as  $column){
        
            $operation [$column->Field] = $column->Field;

        }
        return $operation;
    }

    public static function getAttributFromModel($attribute)
    {
        if(is_object(json_decode($attribute))){

            $attributeFromModel = json_decode($attribute)->en;
        }else{
            $attributeFromModel = $attribute;
        }

        return $attributeFromModel;
    }

    public  static function sendSMS($parameters)
    {
        $SMS = Request::create(route('sendSMS'),'post',
        [
            'name' =>  $parameters['name'],
            'message' => $parameters['message'],
            '_token' => csrf_token(),
        ]);

        $responseEmail = app()->handle($SMS);
    }

    public  static function sendEmail($parameters)
    {
        $email = Request::create(route('sendEmail'),'post',
        [
            'reciver' =>  $parameters['to'],
            'sender' =>   $parameters['from'],
            'body' =>   $parameters['body'],
            '_token' => csrf_token(),
        ]);

        $responseEmail = app()->handle($email);
    }

    public static function addTask($parameters)
    {
        $requestAddSpace = Request::create(route('tms.spaceStoreTest'),'post',
        [
            'name' =>  'a_'.$parameters['name'],
            '_token' => csrf_token(),
        ]);

        $responseAddSpace = app()->handle($requestAddSpace);

        /*$space = Space::where('name',$requestAddSpace->request->get('name'))->first();
        
        $requestAddProject = Request::create(route('tms.addProject'),'post',
        [
            'name' =>  $model.'_'.$model,
            'space' => $space,//$space->toArray()
            '_token' => csrf_token(),
        ]);
            
        $responseAddProject = app()->handle($requestAddProject);
        
        
        $project = EntitiesProject::where('name',$requestAddProject->request->get('name'))->first();
        
        $requestAddTaskList = Request::create(route('tms.addTaskList'),'post',
        [
            'name' =>  "task_list".$model,
            'project' => $project,
            '_token' => csrf_token(),
        ]);
        $responseAddTaskList = app()->handle($requestAddTaskList);
         */   
        // $taskList = TasksList::where('name',$requestAddTaskList->request->get('name'))->first();
       
        // $bridage = bridage::create([
        //     'modelId'=>$model->id,
        //     'modelName'=>$model->name,
        //     'taskName'=>$model->name,
        //     'taskId'=>$taskList->id,
        // ]);
    }
}
