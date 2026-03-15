<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Teacher;

class TeacherControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_teachers_list()
    {
        $response = $this->get(route('teachers.index'));
        $response->assertStatus(200);
    }

    // أضف المزيد من الاختبارات حسب الحاجة
}
