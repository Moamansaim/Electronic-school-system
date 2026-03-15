<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Exam;

class ExamControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_exams_list()
    {
        $response = $this->get(route('exams.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_show_create_exam_page()
    {
        $response = $this->get(route('exams.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_exam()
    {
        $data = [
            'subject_id' => 1,
            'teacher_id' => 1,
            'exam_type' => 'final',
            'date' => '2026-03-15',
            'duration' => 60
        ];
        $response = $this->post(route('exams.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('exams', ['exam_type' => 'final']);
    }

    /** @test */
    public function it_can_show_exam_questions()
    {
        $exam = Exam::factory()->create();
        $response = $this->get(route('exam.questions', $exam->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_show_edit_exam_page()
    {
        $exam = Exam::factory()->create();
        $response = $this->get(route('exams.edit', $exam->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_exam()
    {
        $exam = Exam::factory()->create();
        $data = [
            'subject_id' => $exam->subject_id,
            'teacher_id' => $exam->teacher_id,
            'exam_type' => 'midterm',
            'date' => $exam->date,
            'duration' => $exam->duration
        ];
        $response = $this->put(route('exams.update', $exam->id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('exams', ['exam_type' => 'midterm']);
    }

    /** @test */
    public function it_can_delete_exam()
    {
        $exam = Exam::factory()->create();
        $response = $this->delete(route('exams.destroy', $exam->id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('exams', ['id' => $exam->id]);
    }

    /** @test */
    public function it_can_show_exam_question_create_page()
    {
        $exam = Exam::factory()->create();
        $response = $this->get(route('exams.show', $exam->id));
        $response->assertStatus(200);
    }
}
