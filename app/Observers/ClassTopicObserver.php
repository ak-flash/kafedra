<?php

namespace App\Observers;

use App\Models\Topics\ClassTopic;

class ClassTopicObserver
{
    /**
     * Handle the ClassTopic "created" event.
     *
     * @param ClassTopic $classTopic
     * @return void
     */
    public function created(ClassTopic $classTopic)
    {

    }

    /**
     * Handle the ClassTopic "updated" event.
     *
     * @param ClassTopic $classTopic
     * @return void
     */
    public function updated(ClassTopic $classTopic)
    {
        $classTopic['last_edited_by_id'] = auth()->id();
    }

    /**
     * Handle the ClassTopic "deleted" event.
     *
     * @param ClassTopic $classTopic
     * @return void
     */
    public function deleted(ClassTopic $classTopic)
    {
        //
    }

    /**
     * Handle the ClassTopic "restored" event.
     *
     * @param ClassTopic $classTopic
     * @return void
     */
    public function restored(ClassTopic $classTopic)
    {
        //
    }

    /**
     * Handle the ClassTopic "force deleted" event.
     *
     * @param ClassTopic $classTopic
     * @return void
     */
    public function forceDeleted(ClassTopic $classTopic)
    {
        //
    }
}
