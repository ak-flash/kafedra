<?php

namespace App\Filament\Resources\MCQ\QuestionResource\Pages;

use App\Filament\Resources\MCQ\QuestionResource;
use App\Models\MCQ\Question;
use App\Models\UserDepartment\Discipline;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Arr;

class ImportQuestions extends Page implements HasForms
{
    use HasPageShield;
    use InteractsWithForms;

    public $importText;
    public $sectionId;

    public array $importedQuestions = [];
    public $importedQuestionsIds;

    public bool $previewQuestionsModal = false;

    protected static string $resource = QuestionResource::class;

    public static ?string $title = 'Импортирование вопросов';

    protected static string $view = 'filament.resources.m-c-q.question-resource.pages.import-questions';




    public function mount(): void
    {
        //dd(Redis::connection('cache')->keys('*'));
        //dd(auth()->user()->disciplines_cache->pluck('id'));
        //auth()->user()->departments;
        //dd(ClassTopic::find(1)->discipline);
dd(auth()->user()->is_admin);

    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('sectionId')->label('Раздел')
                ->options(auth()->user()->sections()->pluck('sections.name', 'sections.id'))
                ->required(),

            Forms\Components\Textarea::make('importText')->label('Поле для вставки вопросов с вариантами ответов')->required(),
        ];
    }


    public function previewQuestions()
    {
        $this->previewQuestionsModal = true;

        $i = 0;
        $s = 0;

        $separator = "\n";

        $questions = [];

        $line = strtok($this->importText, $separator);

        while ($line !== false) {
            $i++;

            $s = $s > 4 ? 0 : $s;

            if($s === 0){

                $questions[$i] = [
                    'question' => $line,
                    'difficulty' => 3
                ];

                $checkDuplicate = Question::where('question', 'LIKE', '%'.$line.'%')->get(['id', 'question', 'answers']);

                if($checkDuplicate->isNotEmpty()) {
                    $questions[$i] = array_merge($questions[$i], [ 'duplicated' => $checkDuplicate ]);
                }




            } else {
                /*$question->answers()->create([
                    'answer' => $line
                ]);*/
                $questions[$i-$s]['answers'][] = [ 'answer' => $line ];
            }

            $s++;

            $line = strtok( $separator );
        }

        $this->importedQuestions = $questions;
    }

    public function store()
    {
        $questions = array();

        foreach ($this->importedQuestions as $record) {

            if (! empty($record['answers'])) {
                $questions[] = Question::create([
                    'question' => $record['question'],
                    'section_id' => $this->sectionId,
                    'type_id' => 1,
                    'user_id' => auth()->id(),
                    'answers' => $record['answers'],
                    'difficulty' => $record['difficulty'] ?? 3,
                ]);
            }

        }

        if(! empty($questions)) {
            $this->importedQuestionsIds = collect($questions)->pluck('id');
        }


        $this->previewQuestionsModal = false;

        $this->reset(['importText', 'importedQuestions']);


    }

    public function excludeQuestion($index)
    {
        unset($this->importedQuestions[$index]);
    }
}
