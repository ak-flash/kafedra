<?php

namespace App\Observers;

use App\Models\MCQ\Variant;

class VariantObserver
{
    /**
     * Handle the Variant "created" event.
     *
     * @param  \App\Models\MCQ\Variant  $variant
     * @return void
     */
    public function created(Variant $variant)
    {
        //
    }

    /**
     * Handle the Variant "updated" event.
     *
     * @param  \App\Models\MCQ\Variant  $variant
     * @return void
     */
    public function updated(Variant $variant)
    {
        //
    }

    /**
     * Handle the Variant "deleted" event.
     *
     * @param  \App\Models\MCQ\Variant  $variant
     * @return void
     */
    public function deleted(Variant $variant)
    {
        //
    }

    /**
     * Handle the Variant "restored" event.
     *
     * @param  \App\Models\MCQ\Variant  $variant
     * @return void
     */
    public function restored(Variant $variant)
    {
        //
    }

    /**
     * Handle the Variant "force deleted" event.
     *
     * @param  \App\Models\MCQ\Variant  $variant
     * @return void
     */
    public function forceDeleted(Variant $variant)
    {
        //
    }
}
