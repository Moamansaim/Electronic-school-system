<?php

namespace Database\Factories;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Factories\Factory;

class ExamFactory extends Factory
{
    protected $model = Exam::class;

    public function definition()
    {
        return [
            'subject_id' => 1,
            'teacher_id' => 1,
            'exam_type' => 'final',
            'date' => $this->faker->date(),
            'duration' => $this->faker->numberBetween(30, 120),
        ];
    }
}
