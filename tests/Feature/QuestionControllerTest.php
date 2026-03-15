<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Question;

class QuestionControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_questions_list()
    {
        $response = $this->get(route('questions.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_show_create_question_page()
    {
        $response = $this->get(route('questions.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_question()
    {
        $data = [
            'question_text' => 'ما هو عاصمة مصر؟',
            'question_type' => 'multiple_choice',
            'exam_id' => 1,
            'options' => ['القاهرة', 'الإسكندرية', 'أسوان'],
            'is_correct' => 0
        ];
        $response = $this->post(route('questions.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('questions', ['question_text' => 'ما هو عاصمة مصر؟']);
    }

    /** @test */
    public function it_can_show_edit_question_page()
    {
        $question = Question::factory()->create();
        $response = $this->get(route('questions.edit', $question->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_question()
    {
        $question = Question::factory()->create();
        $data = [
            'question_text' => 'ما هو عاصمة السعودية؟',
            'question_type' => $question->question_type,
            'exam_id' => $question->exam_id,
            'options' => ['الرياض', 'جدة', 'مكة'],
            'is_correct' => 0
        ];
        $response = $this->put(route('questions.update', $question->id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('questions', ['question_text' => 'ما هو عاصمة السعودية؟']);
    }

    /** @test */
    public function it_can_delete_question()
    {
        $question = Question::factory()->create();
        $response = $this->delete(route('questions.destroy', $question->id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('questions', ['id' => $question->id]);
    }
}
