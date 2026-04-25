<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\ClassSchedule;
use App\Models\GradeLevel;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

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
        $user = User::create([
            'school_id' => 'jhu123654789',
            'password' => Hash::make(123654)
        ]);
        $teacher = Teacher::cretae([
            'first_name' => 'محمد',
            'father_name' => 'ffff',
            'grandfather_name' => 'xxxxx',
            'family_name' => 'sssss',
            'date_of_birth' => '2013-10-05',
            'national_id' => '123654789',
            'city' => 'xxxx',
            'district' => 'xxx',
            'street' => 'xxxx',
            'user_id' => $user->id
        ]);
        $grade_level = GradeLevel::create([
            'name' => 'أولى اعدادي',
        ]);
        $classroom = Classroom::create([
            'name' => 'الصف العاشر 10',
            'grade_level_id' => $grade_level->id,
            'teacher_id' => $teacher->id,
        ]);
        $data = [
            'classroom_id' => $classroom->id,
            'teacher_id' => $teacher->id,
            'day' => 'الأحد',
            'class_schedule' => 'الحصة الأولى'
        ];
        $response = $this->post(route('class-schedules.store'), $data);
        $response->assertStatus(201);
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
        $user = User::create([
            'school_id' => 'jhu123654789',
            'password' => Hash::make(123654)
        ]);
        $teacher = Teacher::cretae([
            'first_name' => 'محمد',
            'father_name' => 'ffff',
            'grandfather_name' => 'xxxxx',
            'family_name' => 'sssss',
            'date_of_birth' => '2013-10-05',
            'national_id' => '123654789',
            'city' => 'xxxx',
            'district' => 'xxx',
            'street' => 'xxxx',
            'user_id' => $user->id
        ]);
        $grade_level = GradeLevel::create([
            'name' => 'أولى اعدادي',
        ]);
        $classroom = Classroom::create([
            'name' => 'الصف العاشر 10',
            'grade_level_id' => $grade_level->id,
            'teacher_id' => $teacher->id,
        ]);
        $class_schedule = ClassSchedule::create([
            'classroom_id' => $classroom->id,
            'teacher_id' => $teacher->id,
            'day' => 'السبت',
            'class_schedule' => 'الحصة الأولى'
        ]);

        $response = $this->put(route('class-schedules.update', $class_schedule->id), [
            'classroom_id' => $classroom->id,
            'teacher_id' => $teacher->id,
            'day' => 'الأحد',
            'class_schedule' => 'الحصة الأولى'
        ]);
        $response->assertStatus(204);
        $this->assertDatabaseHas('class_schedules', ['day' => 'الأحد']);
    }

    /** @test */
    public function it_can_delete_class_schedule()
    {
        $user = User::create([
            'school_id' => 'jhu123654789',
            'password' => Hash::make(123654)
        ]);
        $teacher = Teacher::cretae([
            'first_name' => 'محمد',
            'father_name' => 'ffff',
            'grandfather_name' => 'xxxxx',
            'family_name' => 'sssss',
            'date_of_birth' => '2013-10-05',
            'national_id' => '123654789',
            'city' => 'xxxx',
            'district' => 'xxx',
            'street' => 'xxxx',
            'user_id' => $user->id
        ]);
        $grade_level = GradeLevel::create([
            'name' => 'أولى اعدادي',
        ]);
        $classroom = Classroom::create([
            'name' => 'الصف العاشر 10',
            'grade_level_id' => $grade_level->id,
            'teacher_id' => $teacher->id,
        ]);
        $class_schedule = ClassSchedule::create([
            'classroom_id' => $classroom->id,
            'teacher_id' => $teacher->id,
            'day' => 'السبت',
            'class_schedule' => 'الحصة الأولى'
        ]);
        $response = $this->delete(route('class-schedules.destroy', $class_schedule->id));
        $response->assertStatus(204);
        $this->assertDatabaseMissing('class_schedules', ['id' => $class_schedule->id]);
    }
}