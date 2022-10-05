<?php

namespace App\Observers;

use App\Models\Topics\LectureTopic;

class LectureTopicObserver
{
    /**
     * Handle the LectureTopic "created" event.
     *
     * @param  \App\Models\Topics\LectureTopic  $lectureTopic
     * @return void
     */
    public function created(LectureTopic $lectureTopic)
    {
        //
    }

    /**
     * Handle the LectureTopic "updated" event.
     *
     * @param  \App\Models\Topics\LectureTopic  $lectureTopic
     * @return void
     */
    public function updated(LectureTopic $lectureTopic)
    {
        //
    }

    /**
     * Handle the LectureTopic "deleted" event.
     *
     * @param  \App\Models\Topics\LectureTopic  $lectureTopic
     * @return void
     */
    public function deleted(LectureTopic $lectureTopic)
    {
        //
    }

    /**
     * Handle the LectureTopic "restored" event.
     *
     * @param  \App\Models\Topics\LectureTopic  $lectureTopic
     * @return void
     */
    public function restored(LectureTopic $lectureTopic)
    {
        //
    }

    /**
     * Handle the LectureTopic "force deleted" event.
     *
     * @param  \App\Models\Topics\LectureTopic  $lectureTopic
     * @return void
     */
    public function forceDeleted(LectureTopic $lectureTopic)
    {
        //
    }
}
