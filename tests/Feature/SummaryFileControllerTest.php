<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\SummaryFile;

class SummaryFileControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_upload_summary_file()
    {
        // اختبار رفع ملف ملخص
        $response = $this->post(route('summary.store'), []);
        $response->assertStatus(422); // يجب أن يفشل بسبب نقص البيانات
    }

    /** @test */
    public function it_can_show_file_summary_page()
    {
        $summaryFile = SummaryFile::factory()->create();
        $response = $this->get(route('summary.view', $summaryFile->subject_id));
        $response->assertStatus(200);
    }

    /** @test */
    public function it_can_delete_summary_file()
    {
        $summaryFile = SummaryFile::factory()->create();
        $response = $this->delete(route('summary.destroy', $summaryFile->id));
        $response->assertStatus(302);
        $this->assertDatabaseMissing('summary_files', ['id' => $summaryFile->id]);
    }

    // أضف المزيد من الاختبارات حسب الحاجة
}
