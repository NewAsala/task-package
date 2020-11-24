<?php

namespace Akrad\Bridage\Http\Controllers;

use Akrad\Bridage\Events\Project;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class modelController extends Controller
{
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

    public function send(Request $request)
    {
        if($request->events  == "create"){
            // dd($request->events);
            event(new Project($request));

            
        }else if($request->events == "delete"){
            dd("delete");
        }else if($request->events == "update")
        {
            dd("update");

        }else{
            dd("false");
        }
    }
}


