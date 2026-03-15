<?php

namespace Database\Factories;

use App\Models\Student;
use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory
{
    protected $model = Student::class;

    public function definition()
    {
        return [
            'first_name' => $this->faker->firstName(),
            'father_name' => $this->faker->firstName(),
            'grandfather_name' => $this->faker->firstName(),
            'family_name' => $this->faker->lastName(),
            'date_of_birth' => $this->faker->date(),
            'national_id' => $this->faker->unique()->numerify('##########'),
            'city' => $this->faker->city(),
            'district' => $this->faker->word(),
            'street' => $this->faker->streetName(),
            'grade_level_id' => 1,
            'classroom_id' => 1
        ];
    }
}
