<?php

namespace Tests\Feature;

use App\Models\Classroom;
use App\Models\GradeLevel;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class ClassroomTest extends TestCase
{
    use RefreshDatabase;

    //  * A basic feature test example.
    //  */
    public function test_user_can_view_classroom_add_new_one_and_redirect_back(): void
    {
        $response = $this->get('cms/classrooms');
        $response->assertStatus(200);

        $grade_level = GradeLevel::create([
            'name' => 'أولى ثانوي',
        ]);

        $user = User::create([
            'school_id' => Str::random(12),
            'password' => Hash::make(123456789),
        ]);

        $teacher = Teacher::create([
            'first_name' => 'محمد',
            'father_name' => 'خالد',
            'grandfather_name' => 'محمد',
            'family_name' => 'سعيد',
            'date_of_birth' => '2013-10-05',
            'national_id' => '123654789',
            'city' => 'غزة',
            'district' => 'الرمال',
            'street' => 'الجلاء',
            'user_id' => $user->id,
        ]);

        $data = [
            'name' => 'الصف العاشر 10',
            'grade_level_id' => $grade_level->id,
            'teacher_id' => $teacher->id,
        ];

        $post_response = $this->post('cms/classrooms', $data);

        $post_response->assertRedirect('cms/classrooms');

        $final_response = $this->followRedirects($post_response);

        $final_response->assertSee('الصف العاشر 10');

        $this->assertDatabaseHas('classrooms', [
            'name' => 'الصف العاشر 10',
        ]);
    }

    public function test_user_can_view_classroom_add_and_edit_and_redirect_back(): void
    {
        $response = $this->get('cms/classrooms');
        $response->assertStatus(200);

        $grade_level = GradeLevel::create([
            'name' => 'أولى ثانوي',
        ]);

        $user = User::create([
            'school_id' => Str::random(12),
            'password' => Hash::make(123456789),
        ]);

        $teacher = Teacher::create([
            'first_name' => 'محمد',
            'father_name' => 'خالد',
            'grandfather_name' => 'خالد',
            'family_name' => 'سعيد',
            'date_of_birth' => '2013-10-05',
            'national_id' => '123654789',
            'city' => 'غزة',
            'district' => 'الرمال',
            'street' => 'الجلاء',
            'user_id' => $user->id,
        ]);

        $classroom = Classroom::create([
            'name' => 'الصف الحادي عشر 10',
            'grade_level_id' => $grade_level->id,
            'teacher_id' => $teacher->id,
        ]);

        $post_response = $this->get("cms/classrooms/{$classroom->id}/edit");

        $update_response = $this->put("cms/classrooms/{$classroom->id}", [
            'name' => 'الصف الثاني عشر 12',
            'grade_level_id' => $grade_level->id,
            'teacher_id' => $teacher->id,
        ]);

        $update_response->assertRedirect(route('classrooms.index'));

        $final_response = $this->followRedirects($update_response);

        $final_response->assertSee('الصف الثاني عشر 12');
        $final_response->assertDontSee('الصف الحادي عشر 12');

        $this->assertDatabaseHas('classrooms', [
            'name' => 'الصف الثاني عشر 12',
            'grade_level_id' => $grade_level->id,
            'teacher_id' => $teacher->id,
        ]);
    }

    public function test_user_can_view_classroom_add_and_delete_and_redirect_back(): void
    {
        $response = $this->get('cms/classrooms');
        $response->assertStatus(200);

        $grade_level = GradeLevel::create([
            'name' => 'أولى ثانوي',
        ]);

        $user = User::create([
            'school_id' => Str::random(12),
            'password' => Hash::make(123456789),
        ]);

        $teacher = Teacher::create([
            'first_name' => 'محمد',
            'father_name' => 'خالد',
            'grandfather_name' => 'خالد',
            'family_name' => 'سعيد',
            'date_of_birth' => '2013-10-05',
            'national_id' => '123654789',
            'city' => 'غزة',
            'district' => 'الرمال',
            'street' => 'الجلاء',
            'user_id' => $user->id,
        ]);

        $classroom = Classroom::create([
            'name' => 'الصف الحادي عشر 10',
            'grade_level_id' => $grade_level->id,
            'teacher_id' => $teacher->id,
        ]);

        $delete_response = $this->delete("cms/classrooms/{$classroom->id}");

        $delete_response->assertRedirect(route('classrooms.index'));

        $final_response = $this->followRedirects($delete_response);

        $final_response->assertDontSee('الصف الثاني عشر 12');

        $this->assertSoftDeleted('classrooms', [
            'name' => 'الصف الحادي عشر 10',
            'grade_level_id' => $grade_level->id,
            'teacher_id' => $teacher->id,
        ]);
    }
}
