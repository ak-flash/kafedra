<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class SelectDiscipline extends Widget
{
    protected static string $view = 'filament.widgets.select-discipline';

    public $disciplineId;
    public $disciplinesList;

    public function mount()
    {
        $this->disciplinesList = \App\Services\EducationService::getDisciplinesWithFaculties();
    }

    public function updatedDisciplineId($value)
    {
        $this->dispatch('disciplineSelected', $value);

    }
}
