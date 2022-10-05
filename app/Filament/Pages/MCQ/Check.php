<?php

namespace App\Filament\Pages\MCQ;

use App\Models\MCQ\Variant;
use App\Models\Topics\ClassTopic;
use App\Services\UserService;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Pages\Page;


class Check extends Page
{
    use HasPageShield;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-check';

    protected static ?string $navigationLabel = 'Проверка';

    protected static ?string $navigationGroup = 'Тесты';

    public static ?string $title = 'Проверка тестов';

    protected static ?string $slug = 'mcq-check';

    protected static string $view = 'filament.pages.m-c-q.check';

    public $variant;
    public $variantId;
    public $variantsList;

    public $disciplinesList;
    public $disciplineId;
    public $topicsList;
    public $topicId;

    public $studentValues = [];
    public $result = [];

    public bool $mode = false;
    public bool $selectById = false;
    public bool $showResultVariant = false;

    protected $messages = [
        'studentValues.*.answers.required' => 'Укажите номер ответа студента',
    ];

    public function mount()
    {
        $disciplines = auth()->user()->disciplines_cache->pluck('id');

        /*$this->variantsList = Variant::whereHas('class_topic.discipline', function ($query) use ($disciplines) {
            return $query->whereIn('discipline_id', $disciplines);
        })->pluck('id');*/

        $this->disciplinesList = \App\Services\UserService::getDisciplinesWithFaculties();
        //dd($this->variants);
    }


    public function loadVariant()
    {
        $this->validate([
            'variantId' => 'required|numeric',
        ]);

        $this->variant = Variant::find($this->variantId);



        if (empty($this->variant)) {
            $this->addError('variantId', 'Вариант не найден...');

            return;
        }

        if (! in_array($this->variant->class_topic->discipline_id, auth()->user()->disciplines_cache->pluck('id')->toArray())) {
            $this->variant = null;
            $this->addError('variantId', 'Ошибка доступа к данному варианту...');
            return;
        }


        $this->fillStudentValues($this->variant->questions);
    }

    public function check()
    {
        $this->clearFromEmptyValues();

        $this->validate([
            'studentValues.*.answers' => 'required',
            'studentValues.*.answers.*' => 'required',
        ]);

        $this->result = [];
        $totalPoints = 0;

        foreach ($this->variant->questions as $question) {

            $studentAnswers = $this->studentValues[$question['id']]['answers'];

            $points = 1;

            if(count($question['correct']) < count($studentAnswers)) {

                foreach ($studentAnswers as $answer) {

                    if(! in_array($answer, $question['correct'])) {
                        $points = $points - 1 / count($studentAnswers);
                    }

                    $this->result[$question['id']] = [
                        'points' => round($points, 1),
                    ];

                }
            }

            if(count($question['correct']) >= count($studentAnswers)) {


                foreach ($question['correct'] as $correct) {

                    if(! in_array($correct, $studentAnswers)) {
                        $points = $points - 1 / count($question['correct']);
                    }

                    $this->result[$question['id']] = [
                        'points' => round($points, 1),
                    ];

                }
            }

            $this->result[$question['id']]['correct'] = $points >= 1;

            if(count($question['answers']) === count($studentAnswers)) {
                $this->result[$question['id']] = [
                    'correct' => false,
                    'points' => 0,
                ];

                $points = 0;
            }

            $totalPoints = $totalPoints + $points;

        }

        $this->result['totalPoints'] = $totalPoints;

        $this->clearValidation();

        //dd($this->result);

        //dd($this->variant->questions);
        //dd($this->studentValues);
    }

    public function updatedDisciplineId($value)
    {
        $this->topicsList = ClassTopic::where('discipline_id', $value)->orderBy('sort_order')->get(['title','id','sort_order']);
    }

    public function updatedTopicId()
    {
        $variantsList = Variant::where('class_topic_id', $this->topicId)->whereNotNull('variant')->orderBy('variant')->pluck('variant', 'id');

        $this->variantsList = make_options_for_ui_select($variantsList);
    }

    public function updatedVariantId()
    {
        $this->loadVariant();
    }

    public function updatedMode()
    {
        $this->clearStudentValues();
    }

    public function clearStudentValues()
    {
        $this->studentValues = [];
        $this->result = [];
        $this->fillStudentValues($this->variant->questions);
    }


    public function clearFromEmptyValues()
    {
        foreach($this->studentValues as $key => $value) {
            foreach ($value['answers'] as $index => $answer) {
                if(empty($answer))
                    unset($this->studentValues[$key]['answers'][$index]);
            }
        }
    }
    public function fillStudentValues($questions)
    {
        foreach ($questions as $key => $question) {
            $this->studentValues[$question['id']] = [
                'answers' => [],
            ];
        }
    }

    public function openResultModal()
    {
        $this->showResultVariant = true;
    }

    public function calculatePoints($result)
    {
        return round($result['totalPoints'] * 100 / count($this->variant->questions));
    }
}
