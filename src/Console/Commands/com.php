<?php

namespace App\Console\Commands;

use Akrad\Bridage\Models\Models;
use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use ReflectionClass;
use Str;
class com extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:observer {name : name of the observer.} {--class= : name of the model} {except?* : generate all Eloquent events except for specific ones.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $events = [
        'retrieved', 'creating', 'created', 'updating', 'updated',
        'saving', 'saved', 'restoring', 'restored',
        'deleting', 'deleted', 'forceDeleted',
    ];

    protected $description = 'Create a new observer class';
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(Filesystem $filesystem) {
        parent::__construct();
        $this->filesystem = $filesystem;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle() {

        $this->makeFolderIfNotExist();

        $object = explode('\\',$this->argument('name'));
        $nameObject = $object[count($object)-1];
        $observerName = $nameObject.'Observer';

        if (!$this->filesystem->exists(app_path('Observers'. DIRECTORY_SEPARATOR . $nameObject . 'Observer.php'))) {
            
            $hasModel = trim($this->option('class')) != ''?trim($this->option('class')):null;

            if ($hasModel) {
                
                $modelPath = $this->argument('name');
                $model =new $modelPath;
                $model = $model->getObservableEvents();
                $loweredCaseModel = lcfirst($this->option('class'));

            }else{
                $model = $this->events;
            }
            
            $this->createObserveForModel($model,$hasModel,$loweredCaseModel,$modelPath,$nameObject);

            $this->callObserveInEventServiceProvider($modelPath,$hasModel,$observerName);

            $this->info('Observer Created !');

        }else{
            $this->info('Observer Already Exists ! , Wake up you need some caffeine :)');
        }

    }

    public function makeFolderIfNotExist ()
    {
        if (!$this->filesystem->isDirectory(app_path('Observers'))) {
            $this->filesystem->makeDirectory(app_path('Observers'), 0755, false, true);
            $this->info('Observers folder generated !');
        }
    }

    public function createObserveForModel($model,$hasModel,$loweredCaseModel,$modelPath,$nameObject)
    {
        $fileContent = '';

        $if = function ($condition, $applied, $rejected) {
            return $condition?$applied:$rejected;
        };

        foreach ($model as $event) {
            if (in_array($event, $this->argument('except'))) {
                continue;
            }
            $fileContent .= <<<Event
                
            public function {$event}({$if($hasModel, "$hasModel", '')} {$if($hasModel, "\$$loweredCaseModel", '')}){
                    self::checkRule(\$$loweredCaseModel,"$event");            
            }
            Event;
        }
        $fileContent = <<<content
        <?php
            namespace App\Observers;
            {$if(isset($hasModel) && !empty($hasModel), "use $modelPath;\n"."use Akrad\Bridage\Observers\abstractObserver;\n", '')}
            class {$nameObject}Observer extends abstractObserver {
                {$fileContent}
            }
        ?>
        content;
        $this->filesystem->put(app_path('Observers'. DIRECTORY_SEPARATOR . $nameObject . 'Observer.php'), $fileContent);      
    }

    public function callObserveInEventServiceProvider($modelPath,$hasModel,$observerName)
    {
        if($this->filesystem->exists(app_path('Providers'. DIRECTORY_SEPARATOR .'EventServiceProvider.php')))
        {
            $EventServiceProviderPath = '..\\app\\Providers\\EventServiceProvider.php';

            $reflection = new ReflectionClass('app\\Providers\\EventServiceProvider');
            $methods = $reflection->getMethods();
            
            foreach($methods as $method){

                $all_lines = file($EventServiceProviderPath);
                if($method->getName() == "boot")
                {
                    $replace = "\t$hasModel::observe($observerName::class);\n"."\t}\n"; 
                    file_put_contents($EventServiceProviderPath, str_replace($all_lines[$method->getEndLine()-1],$replace, file_get_contents($EventServiceProviderPath)));
                }
            }

            $nameSpace = $reflection->getNamespaceName();
            $useModelPath = "use $modelPath;";
            $useobservePath = "use App\\Observers\\$observerName;";
            $nameSpaceReplace = $nameSpace.";\n"."$useModelPath\n"."$useobservePath\n";
            file_put_contents($EventServiceProviderPath, str_replace($nameSpace.";" ,$nameSpaceReplace, file_get_contents($EventServiceProviderPath)));

        }
    }
}
