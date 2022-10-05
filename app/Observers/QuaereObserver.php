<?php

namespace App\Observers;

use App\Models\Topics\Quaere;

class QuaereObserver
{
    /**
     * Handle the Quaere "created" event.
     *
     * @param  \App\Models\Topics\Quaere  $quaere
     * @return void
     */
    public function created(Quaere $quaere)
    {
        //
    }

    /**
     * Handle the Quaere "updated" event.
     *
     * @param  \App\Models\Topics\Quaere  $quaere
     * @return void
     */
    public function updated(Quaere $quaere)
    {
        //
    }

    /**
     * Handle the Quaere "deleted" event.
     *
     * @param  \App\Models\Topics\Quaere  $quaere
     * @return void
     */
    public function deleted(Quaere $quaere)
    {
        //
    }

    /**
     * Handle the Quaere "restored" event.
     *
     * @param  \App\Models\Topics\Quaere  $quaere
     * @return void
     */
    public function restored(Quaere $quaere)
    {
        //
    }

    /**
     * Handle the Quaere "force deleted" event.
     *
     * @param  \App\Models\Topics\Quaere  $quaere
     * @return void
     */
    public function forceDeleted(Quaere $quaere)
    {
        //
    }
}
