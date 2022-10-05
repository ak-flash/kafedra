<?php

namespace App\Providers;

use App\Models\Common\Department;
use App\Models\Common\Faculty;
use App\Models\MCQ\Question;
use App\Models\MCQ\Variant;
use App\Models\Topics\ClassTopic;
use App\Models\Topics\LectureTopic;
use App\Models\Topics\Quaere;
use App\Models\UserDepartment\Discipline;
use App\Models\UserDepartment\Section;
use App\Observers\ClassTopicObserver;
use App\Observers\DepartmentObserver;
use App\Observers\DisciplineObserver;
use App\Observers\FacultyObserver;
use App\Observers\LectureTopicObserver;
use App\Observers\QuaereObserver;
use App\Observers\QuestionObserver;
use App\Observers\SectionObserver;
use App\Observers\VariantObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Faculty::observe(FacultyObserver::class);
        Department::observe(DepartmentObserver::class);
        Section::observe(SectionObserver::class);
        Discipline::observe(DisciplineObserver::class);
        ClassTopic::observe(ClassTopicObserver::class);
        LectureTopic::observe(LectureTopicObserver::class);
        Quaere::observe(QuaereObserver::class);
        Question::observe(QuestionObserver::class);
        Variant::observe(VariantObserver::class);
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {
        return false;
    }
}
