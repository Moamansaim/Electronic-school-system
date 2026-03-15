<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_show_profile_page()
    {
        $response = $this->get(route('profile.show'));
        $response->assertStatus(200);
    }

    // أضف المزيد من الاختبارات حسب الحاجة
}
