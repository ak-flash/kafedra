<?php

namespace App\Observers;

use App\Models\UserDepartment\Section;
use Illuminate\Support\Facades\Cache;

class SectionObserver
{
    /**
     * Handle the Section "created" event.
     *
     * @param  Section  $section
     * @return void
     */
    public function created(Section $section)
    {
        $this->clearCache();
    }

    /**
     * Handle the Section "updated" event.
     *
     * @param  Section  $section
     * @return void
     */
    public function updated(Section $section)
    {
        $this->clearCache();
    }

    /**
     * Handle the Section "deleted" event.
     *
     * @param  Section  $section
     * @return void
     */
    public function deleted(Section $section)
    {
        $this->clearCache();
    }

    /**
     * Handle the Section "restored" event.
     *
     * @param  Section  $section
     * @return void
     */
    public function restored(Section $section)
    {
        $this->clearCache();
    }

    /**
     * Handle the Section "force deleted" event.
     *
     * @param  Section  $section
     * @return void
     */
    public function forceDeleted(Section $section)
    {
        $this->clearCache();
    }

    public function clearCache(): void
    {
        Cache::tags('sectionsUserList')->flush();
    }
}
