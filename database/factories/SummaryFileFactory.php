<?php

namespace Database\Factories;

use App\Models\SummaryFile;
use Illuminate\Database\Eloquent\Factories\Factory;

class SummaryFileFactory extends Factory
{
    protected $model = SummaryFile::class;

    public function definition()
    {
        return [
            'user_id' => 1,
            'subject_id' => 1,
            'file_path' => $this->faker->word() . '.pdf',
            'file_name' => $this->faker->word() . '.pdf',
        ];
    }
}
