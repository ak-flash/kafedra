<?php

namespace App\Livewire;

use App\Services\EducationService;
use Livewire\Component;

class Reclasses extends Component
{
    public $disciplines;

    public function mount() {
        $this->disciplines = $this->getDisciplinesWithFaculties();
    }

    public function render()
    {
        return view('livewire.reclasses')
            ->layout('layouts.guest');
    }

    public function getDisciplinesWithFaculties()
    {
        return [];
    }
}
