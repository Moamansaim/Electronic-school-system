<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Student;

class StudentControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_students_list()
    {
        $response = $this->get(route('students.index'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_show_create_student_page()
    {
        $response = $this->get(route('students.create'));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_store_student()
    {
        $data = [
            'first_name' => 'محمد',
            'father_name' => 'أحمد',
            'grandfather_name' => 'سعيد',
            'family_name' => 'الأنصاري',
            'date_of_birth' => '2010-01-01',
            'national_id' => '1234567890',
            'city' => 'الرياض',
            'district' => 'النخيل',
            'street' => 'شارع الملك',
            'grade_level_id' => 1,
            'classroom_id' => 1
        ];
        $response = $this->post(route('students.store'), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('students', ['first_name' => 'محمد']);
    }

    /** @test */
    public function it_can_show_edit_student_page()
    {
        $student = Student::factory()->create();
        $response = $this->get(route('students.edit', $student->id));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_update_student()
    {
        $student = Student::factory()->create();
        $data = [
            'first_name' => 'عبدالله',
            'father_name' => $student->father_name,
            'grandfather_name' => $student->grandfather_name,
            'family_name' => $student->family_name,
            'date_of_birth' => $student->date_of_birth,
            'national_id' => $student->national_id,
            'city' => $student->city,
            'district' => $student->district,
            'street' => $student->street,
            'grade_level_id' => $student->grade_level_id,
            'classroom_id' => $student->classroom_id
        ];
        $response = $this->put(route('students.update', $student->id), $data);
        $response->assertStatus(302);
        $this->assertDatabaseHas('students', ['first_name' => 'عبدالله']);
    }

    /** @test */
    public function it_can_delete_student()
    {
        $student = Student::factory()->create();
        $response = $this->delete(route('students.destroy', $student->id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('students', ['id' => $student->id]);
    }
}
