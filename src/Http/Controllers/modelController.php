<?php

namespace Akrad\Bridage\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class modelController extends Controller
{
    function getAllModels(): array
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
                    ->toArray(), $models);
            }
            return $models;
    }
}


