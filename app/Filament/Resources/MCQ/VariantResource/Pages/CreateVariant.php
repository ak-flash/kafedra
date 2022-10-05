<?php

namespace App\Filament\Resources\MCQ\VariantResource\Pages;

use App\Filament\Resources\MCQ\VariantResource;
use App\Models\MCQ\Question;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Builder;

class CreateVariant extends CreateRecord
{
    protected static string $resource = VariantResource::class;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();

        $questionsList = [];

        $questions = $this->getQuestions($data['randomizerType'], $data);

        foreach ($questions as $question) {
            $answers = $question->answers;
            $randomAnswers = [];
            $numberOfCorrect = [];

            // Randomize answers!
            shuffle($answers);

            foreach ($answers as $key => $value) {
                $randomAnswers[] = $value['answer'];

                if(isset($value['is_correct']) && $value['is_correct']) {
                    $numberOfCorrect[] = $key;
                }

            }

            $questionsList[] = [
                'id' => $question->id,
                'question'=> $question->question,
                'answers' => $randomAnswers,
                'correct' => $numberOfCorrect
            ];
        }

        $data['questions'] = $questionsList;

        return $data;
    }

    public function getQuestions($mode, $data)
    {

        if($mode == 'random') {
            $questions = $this->getRandomQuestions($data);
        } else {
            $questions = $this->getChoosedQuestions($data);
        }

        $questionsPerVariant = $this->getPerVariantCount($questions, $data);

        return $questions->random($questionsPerVariant)->values();
    }

    public function getRandomQuestions($data)
    {
        return Question::whereHas('class_topics', function (Builder $query) use ($data) {
            $query->where('class_topic_id', $data['class_topic_id']);
        })->get();

    }

    public function getChoosedQuestions($data)
    {
        return Question::whereIn('id', $data['questions'])->get();
    }

    public function getPerVariantCount($questions, $data)
    {
        return ($questions->count() < $data['questionsPerVariant']) ? $questions->count() : $data['questionsPerVariant'];
    }
}
