<?php

namespace App\Observers;




use App\Models\Common\Department;
use Illuminate\Support\Facades\Cache;

class DepartmentObserver
{
    /**
     * Handle the Department "created" event.
     *
     * @param Department $department
     * @return void
     */
    public function created(Department $department)
    {
        $this->clearCache();
    }

    /**
     * Handle the Department "updated" event.
     *
     * @param Department $department
     * @return void
     */
    public function updated(Department $department)
    {
        $this->clearCache();
    }

    /**
     * Handle the Department "deleting" event.
     *
     * @param Department $department
     * @return void
     */
    public function deleting(Department $department)
    {
        $this->clearCache();
    }

    /**
     * Handle the Department "restored" event.
     *
     * @param Department $department
     * @return void
     */
    public function restored(Department $department)
    {
        $this->clearCache();
    }

    /**
     * Handle the Department "force deleted" event.
     *
     * @param Department $department
     * @return void
     */
    public function forceDeleted(Department $department)
    {
        $this->clearCache();
    }

    public function clearCache(): void
    {
        Cache::tags('departmentsUserList')->flush();
    }
}
