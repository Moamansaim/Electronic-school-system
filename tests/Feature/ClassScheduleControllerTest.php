<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\ClassSchedule;

class ClassScheduleControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_class_schedules_list()
    {
        $response = $this->get(route('class-schedules.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_show_create_class_schedule_page()
    {
        $response = $this->get(route('class-schedules.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_class_schedule()
    {
        $data = [
            'classroom_id' => 1,
            'teacher_id' => 1,
            'subject_id' => 1,
            'day' => 'الأحد',
            'class_schedule' => 'الحصة الأولى'
        ];
        $response = $this->post(route('class-schedules.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('class_schedules', ['day' => 'الأحد']);
    }

    /** @test */
    public function it_can_show_edit_class_schedule_page()
    {
        $classSchedule = ClassSchedule::factory()->create();
        $response = $this->get(route('class-schedules.edit', $classSchedule->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_class_schedule()
    {
        $classSchedule = ClassSchedule::factory()->create();
        $data = [
            'classroom_id' => $classSchedule->classroom_id,
            'teacher_id' => $classSchedule->teacher_id,
            'subject_id' => $classSchedule->subject_id,
            'day' => 'الاثنين',
            'class_schedule' => $classSchedule->class_schedule
        ];
        $response = $this->put(route('class-schedules.update', $classSchedule->id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('class_schedules', ['day' => 'الاثنين']);
    }

    /** @test */
    public function it_can_delete_class_schedule()
    {
        $classSchedule = ClassSchedule::factory()->create();
        $response = $this->delete(route('class-schedules.destroy', $classSchedule->id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('class_schedules', ['id' => $classSchedule->id]);
    }
}
