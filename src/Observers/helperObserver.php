<?php

namespace Akrad\Bridage\Observers;

use Akrad\Bridage\Models\Helper;
use Illuminate\Support\Facades\Artisan;

class helperObserver
{
    /**
     * Handle the Helper "created" event.
     *
     * @param  \App\Models\Helper  $helper
     * @return void
     */
    public function created(Helper $helper)
    {
        $object = explode('\\',$helper->object);
        $name = $object[count($object)-1];

        $exitCode = Artisan::call('make:observer', [
            'name' => $helper->object, '--class' => $name
        ]);
    }

    /**
     * Handle the Helper "updated" event.
     *
     * @param  \App\Models\Helper  $helper
     * @return void
     */
    public function updated(Helper $helper)
    {
        //
    }

    /**
     * Handle the Helper "deleted" event.
     *
     * @param  \App\Models\Helper  $helper
     * @return void
     */
    public function deleted(Helper $helper)
    {
        //
    }

    /**
     * Handle the Helper "restored" event.
     *
     * @param  \App\Models\Helper  $helper
     * @return void
     */
    public function restored(Helper $helper)
    {
        //
    }

    /**
     * Handle the Helper "force deleted" event.
     *
     * @param  \App\Models\Helper  $helper
     * @return void
     */
    public function forceDeleted(Helper $helper)
    {
        //
    }
}
