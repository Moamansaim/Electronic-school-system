<?php

namespace Tests\Feature;

use App\Models\GradeLevel;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GradeLevelTest extends TestCase
{
    use RefreshDatabase;

    //  * A basic feature test example.
    //  */
    public function test_user_can_view_grade_levels_add_new_one_and_redirect_back(): void
    {
        $response = $this->get('cms/grade_levels');
        $response->assertStatus(200);

        $data = [
            'name' => 'ثانية ثانوي',
        ];

        $post_response = $this->post('cms/grade_levels', $data);

        $post_response->assertRedirect('cms/grade_levels');

        $final_response = $this->followRedirects($post_response);

        $final_response->assertSee('ثانية ثانوي');

        $this->assertDatabaseHas('grade_levels', [
            'name' => 'ثانية ثانوي',
        ]);
    }

    public function test_user_can_view_grade_levels_and_edit_and_redirect_back(): void
    {
        $response = $this->get('cms/grade_levels');
        $response->assertStatus(200);

        $data = GradeLevel::create([
            'name' => 'ثالثة ثانوي',
        ]);

        $this->get("cms/grade_levels/{$data->id}/edit")->assertStatus(200);

        $update_response = $this->put("cms/grade_levels/{$data->id}", [
            'name' => 'أولى ثانوي',
        ]);

        $update_response->assertRedirect(route('grade_levels.index'));

        $final_response = $this->followRedirects($update_response);

        $final_response->assertSee('أولى ثانوي');
        $final_response->assertDontSee('ثالثة ثانوي');

        $this->assertDatabaseHas('grade_levels', [
            'id' => $data->id,
            'name' => 'أولى ثانوي',
        ]);
    }

    public function test_user_can_view_grade_levels_and_delete_and_redirect_back()
    {
        $this->get('cms/grade_levels')->assertStatus(200);

        $data = GradeLevel::create([
            'name' => 'أولى اعدادي',
        ]);

        $delete_response = $this->delete("cms/grade_levels/{$data->id}");

        $delete_response->assertRedirect(route('grade_levels.index'));

        $final_response = $this->followRedirects($delete_response);

        $final_response->assertDontSee('أولى اعدادي');

        $this->assertSoftDeleted('grade_levels', [
            'id' => $data->id,
            'name' => 'أولى اعدادي',
        ]);
    }
}