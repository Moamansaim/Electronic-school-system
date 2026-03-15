<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuestionFactory extends Factory
{
    protected $model = Question::class;

    public function definition()
    {
        return [
            'question_text' => $this->faker->sentence(),
            'question_type' => 'multiple_choice',
            'exam_id' => 1,
        ];
    }
}
