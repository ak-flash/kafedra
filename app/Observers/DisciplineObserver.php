<?php

namespace App\Observers;

use App\Models\UserDepartment\Discipline;
use Illuminate\Support\Facades\Cache;

class DisciplineObserver
{
    /**
     * Handle the Discipline "created" event.
     *
     * @param Discipline $discipline
     * @return void
     */
    public function created(Discipline $discipline)
    {
        $this->clearCache();
    }

    /**
     * Handle the Discipline "updated" event.
     *
     * @param Discipline $discipline
     * @return void
     */
    public function updated(Discipline $discipline)
    {
        $this->clearCache();
    }

    /**
     * Handle the Discipline "deleted" event.
     *
     * @param Discipline $discipline
     * @return void
     */
    public function deleted(Discipline $discipline)
    {
        $this->clearCache();
    }

    /**
     * Handle the Discipline "restored" event.
     *
     * @param Discipline $discipline
     * @return void
     */
    public function restored(Discipline $discipline)
    {
        $this->clearCache();
    }

    /**
     * Handle the Discipline "force deleted" event.
     *
     * @param Discipline $discipline
     * @return void
     */
    public function forceDeleted(Discipline $discipline): void
    {
        $this->clearCache();
    }

    public function clearCache(): void
    {
        Cache::tags('disciplinesUserList')->flush();
    }
}
