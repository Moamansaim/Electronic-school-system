<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Classroom;

class ClassroomControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_classrooms_list()
    {
        $response = $this->get(route('classrooms.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_show_create_classroom_page()
    {
        $response = $this->get(route('classrooms.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_classroom()
    {
        $data = [
            'name' => 'فصل تجريبي',
            'grade_level_id' => 1,
            'teacher_id' => null
        ];
        $response = $this->post(route('classrooms.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('classrooms', ['name' => 'فصل تجريبي']);
    }

    /** @test */
    public function it_can_show_edit_classroom_page()
    {
        $classroom = Classroom::factory()->create();
        $response = $this->get(route('classrooms.edit', $classroom->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_classroom()
    {
        $classroom = Classroom::factory()->create();
        $data = [
            'name' => 'فصل معدل',
            'grade_level_id' => $classroom->grade_level_id,
            'teacher_id' => $classroom->teacher_id
        ];
        $response = $this->put(route('classrooms.update', $classroom->id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('classrooms', ['name' => 'فصل معدل']);
    }

    /** @test */
    public function it_can_delete_classroom()
    {
        $classroom = Classroom::factory()->create();
        $response = $this->delete(route('classrooms.destroy', $classroom->id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('classrooms', ['id' => $classroom->id]);
    }
}
