<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\GradeLevel;

class GradeLevelControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_grade_levels_list()
    {
        $response = $this->get(route('grade-levels.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_show_create_grade_level_page()
    {
        $response = $this->get(route('grade-levels.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_grade_level()
    {
        $data = [
            'name' => 'مرحلة تجريبية'
        ];
        $response = $this->post(route('grade-levels.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('grade_levels', ['name' => 'مرحلة التجريبية']);
    }

    /** @test */
    public function it_can_show_edit_grade_level_page()
    {
        $gradeLevel = GradeLevel::factory()->create();
        $response = $this->get(route('grade-levels.edit', $gradeLevel->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_grade_level()
    {
        $gradeLevel = GradeLevel::factory()->create();
        $data = [
            'name' => 'مرحلة معدلة'
        ];
        $response = $this->put(route('grade-levels.update', $gradeLevel->id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('grade_levels', ['name' => 'مرحلة معدلة']);
    }

    /** @test */
    public function it_can_delete_grade_level()
    {
        $gradeLevel = GradeLevel::factory()->create();
        $response = $this->delete(route('grade-levels.destroy', $gradeLevel->id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('grade_levels', ['id' => $gradeLevel->id]);
    }
}
