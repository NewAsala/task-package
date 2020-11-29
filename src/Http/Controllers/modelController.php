<?php

namespace Akrad\Bridage\Http\Controllers;

use Akrad\Bridage\Events\DeleteProject;
use Akrad\Bridage\Events\Project;
use Akrad\Bridage\Events\UpdateProject;
use Akrad\Bridage\Models\Models;
use Illuminate\User;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use LengthException;
use Symfony\Component\Mime\CharacterStream;

class modelController extends Controller
{ 
    /*function  get($path){
    $out = [];
    $results = scandir($path);
    foreach ($results as $result) {
        if ($result === '.' or $result === '..') continue;
        $filename = $path . '/' . $result;
        if (is_dir($filename)) {
            $out = array_merge($out,  $this->get($filename));
        }else{
            $out[] = substr($filename,0,-4);
        }
    }
    return $out;
    }

    function getAllModels(){

        $path = app_path() . "/Models";
        //$v = new $this->get($path)[0];
        dd($this->get($path));

    }*/

    function getAllModels()
    {
        $composer = json_decode(file_get_contents(base_path('composer.json')), true);
        $models = [];

        foreach ((array)data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            $models = array_merge(collect(File::allFiles(base_path($path)))
                ->map(function ($item) use ($namespace) {
                    $path = $item->getRelativePathName();
                    
                    return sprintf('\%s%s',
                        $namespace,
                        strtr(substr($path, 0, strrpos($path, '.')), '/', '\\'));
                })
                ->filter(function ($class) {
                    $valid = false;
                    if (class_exists($class)) {
                        $reflection = new \ReflectionClass($class);
                        $valid = $reflection->isSubclassOf(\Illuminate\Database\Eloquent\Model::class) &&
                            !$reflection->isAbstract();
                    }
                    return $valid;
                })
                ->values()
                ->toArray(),$models);
            }

        return view('bridage::listener')->with('models',$models);
    }

    public function getAttribute($value)
    {
        $path = ucwords(str_replace('_', '\\', $value));
        
        $attribute = Models::makeModel($path);
        dd($attribute->fillable);

    }

    public function send(Request $request)
    {
        if($request->events  == "create"){
           
            event(new Project($request));

        }else if($request->events == "delete"){

            event(new DeleteProject($request));
            
        }else if($request->events == "update")
        {
            event(new UpdateProject($request));

        }else{
            dd("false");
        }
    }
}


