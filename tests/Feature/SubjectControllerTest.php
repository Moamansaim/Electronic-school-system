<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Subject;

class SubjectControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_subjects_list()
    {
        $response = $this->get(route('subjects.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_show_create_subject_page()
    {
        $response = $this->get(route('subjects.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_subject()
    {
        $data = [
            'name' => 'الرياضيات',
            'grade_level_id' => 1
        ];
        $response = $this->post(route('subjects.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('subjects', ['name' => 'الرياضيات']);
    }

    /** @test */
    public function it_can_show_edit_subject_page()
    {
        $subject = Subject::factory()->create();
        $response = $this->get(route('subjects.edit', $subject->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_subject()
    {
        $subject = Subject::factory()->create();
        $data = [
            'name' => 'العلوم',
            'grade_level_id' => $subject->grade_level_id
        ];
        $response = $this->put(route('subjects.update', $subject->id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('subjects', ['name' => 'العلوم']);
    }

    /** @test */
    public function it_can_delete_subject()
    {
        $subject = Subject::factory()->create();
        $response = $this->delete(route('subjects.destroy', $subject->id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
    }
}
