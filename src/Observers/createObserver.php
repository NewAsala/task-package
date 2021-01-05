<?php

namespace Akrad\Bridage\Observers;

use Akrad\Bridage\Models\bridage;
use Akrad\Bridage\Models\Helper;
use Akrad\Bridage\Models\Models;
use Illuminate\Http\Request;
use Modules\TMS\Entities\Project as EntitiesProject;
use Modules\TMS\Entities\Space;
use Modules\TMS\Entities\TasksList;
use Akrad\Bridage\Observers\abstractObserver;
use Hamcrest\Core\IsEqual;
use PHPUnit\Framework\Constraint\IsEqual as ConstraintIsEqual;

class createObserver extends abstractObserver
{
    
    public function created($model)
    {
     //   dd("create",$model);
     
        self::checkRule($model,"create");
    }

    public function updated($model)
    {
       self::checkRule($model,"update");
    }

    /*public function deleted($model)
    {
    }

    public function deleting($model)
    {
    }

    public function restored($model)
    {
    }

    public function forceDeleted($model)
    {
    }*/

}
