<?php

namespace App\Observers;

use App\Models\Common\Faculty;
use Illuminate\Support\Facades\Cache;

class FacultyObserver
{
    /**
     * Handle the Faculty "created" event.
     *
     * @param Faculty $faculty
     * @return void
     */
    public function created(Faculty $faculty)
    {
        $this->clearCache();
    }

    /**
     * Handle the Faculty "updated" event.
     *
     * @param Faculty $faculty
     * @return void
     */
    public function updated(Faculty $faculty)
    {
        $this->clearCache();
    }

    /**
     * Handle the Faculty "deleted" event.
     *
     * @param Faculty $faculty
     * @return void
     */
    public function deleted(Faculty $faculty)
    {
        $this->clearCache();
    }

    /**
     * Handle the Faculty "restored" event.
     *
     * @param Faculty $faculty
     * @return void
     */
    public function restored(Faculty $faculty)
    {
        $this->clearCache();
    }

    /**
     * Handle the Faculty "force deleted" event.
     *
     * @param Faculty $faculty
     * @return void
     */
    public function forceDeleted(Faculty $faculty)
    {
        $this->clearCache();
    }

    public function clearCache(): void
    {
        Cache::tags('facultiesList')->flush();
    }
}
