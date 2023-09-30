<?php

namespace App\Filament\Resources\Kafedra\QuestionResource\Pages;

use App\Filament\Resources\Kafedra\QuestionResource;
use App\Models\Kafedra\Section;
use App\Models\MCQ\Question;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Resources\Pages\Page;

class ImportQuestions extends Page implements HasForms
{
    //use HasPageShield;
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

    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Select::make('sectionId')->label('Раздел')
                ->native(false)
                ->options(Section::where('department_id', Filament::getTenant()->id)->pluck('name', 'id'))
                ->required(),

            Forms\Components\Textarea::make('importText')
                ->label('Поле для вставки вопросов с вариантами ответов')
                ->required()
                ->rows(6),
        ];
    }


    public function previewQuestions(): void
    {
        $this->validate([
            'sectionId' => 'required|min:1',
            'importText' => 'required|min:10',
        ]);

        $this->dispatch('open-modal', id: 'preview-questions');


        $questions = $this->convertTextToQuestion($this->importText);

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


        $this->reset(['importText', 'importedQuestions']);

        $this->dispatch('close-modal', id: 'preview-questions');
    }

    public function excludeQuestion($index)
    {
        unset($this->importedQuestions[$index]);
    }

    public function convertTextToQuestion($text)
    {
        $separator = "\n";
        $i = 0;
        $s = 0;

        $questions = [];

        $line = strtok($text, $separator);

        while ($line !== false) {
            $i++;

            // First line - title, others (2,3,4,5) - answers
            $s = $s > 4 ? 0 : $s;

            // If title - check duplicates
            if($s === 0){

                $questions[$i] = [
                    'question' => $line,
                    'difficulty' => 3
                ];

                if(! empty($this->checkDuplicate($line))) {
                    $questions[$i] = array_merge($questions[$i],
                        [  'duplicated' => $this->checkDuplicate($line) ]);
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

        return $questions;
    }

    public function checkDuplicate($questionTitle)
    {
        $duplicates = Question::where('section_id', $this->sectionId)
            ->where('question', 'LIKE', '%'.$questionTitle.'%')->get(['id', 'question', 'answers']);

        if($duplicates->isNotEmpty()) {
            return $duplicates;
        }


    }
}
