<?php

namespace Tests\Feature;

use App\Models\SchoolContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PanitiaTestimonialManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_panitia_can_open_testimonial_management_page(): void
    {
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);

        $this->actingAs($panitia)
            ->get(route('panitia.contents.index', ['type' => SchoolContent::TYPE_TESTIMONIAL]))
            ->assertOk()
            ->assertSee('Testimoni Orang Tua')
            ->assertSee('Tambah Testimoni');
    }

    public function test_panitia_can_create_update_and_delete_testimonial(): void
    {
        Storage::fake('public');
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);

        $this->actingAs($panitia)
            ->post(route('panitia.contents.store'), [
                'type' => SchoolContent::TYPE_TESTIMONIAL,
                'title' => 'Bunda Aisyah',
                'content' => 'Anak kami semakin percaya diri dan senang belajar.',
                'sort_order' => 1,
                'is_published' => 1,
                'images' => [UploadedFile::fake()->image('bunda-aisyah.jpg')],
            ])
            ->assertRedirect(route('panitia.contents.index', ['type' => SchoolContent::TYPE_TESTIMONIAL]));

        $testimonial = SchoolContent::where('type', SchoolContent::TYPE_TESTIMONIAL)->firstOrFail();

        $this->assertTrue($testimonial->is_published);
        Storage::disk('public')->assertExists($testimonial->image_path);

        $this->actingAs($panitia)
            ->put(route('panitia.contents.update', $testimonial), [
                'type' => SchoolContent::TYPE_TESTIMONIAL,
                'title' => 'Bunda Aisyah Diperbarui',
                'content' => 'Pelayanan guru sangat hangat dan komunikatif.',
                'sort_order' => 2,
            ])
            ->assertRedirect(route('panitia.contents.index', ['type' => SchoolContent::TYPE_TESTIMONIAL]));

        $this->assertDatabaseHas('school_contents', [
            'id' => $testimonial->id,
            'title' => 'Bunda Aisyah Diperbarui',
            'is_published' => false,
            'sort_order' => 2,
        ]);

        $this->actingAs($panitia)
            ->delete(route('panitia.contents.destroy', $testimonial))
            ->assertRedirect(route('panitia.contents.index', ['type' => SchoolContent::TYPE_TESTIMONIAL]));

        $this->assertDatabaseMissing('school_contents', ['id' => $testimonial->id]);
    }

    public function test_home_displays_only_published_testimonials_in_sort_order(): void
    {
        SchoolContent::create([
            'type' => SchoolContent::TYPE_TESTIMONIAL,
            'title' => 'Testimoni Kedua',
            'content' => 'Isi testimoni kedua.',
            'published_at' => now(),
            'is_published' => true,
            'sort_order' => 2,
        ]);

        SchoolContent::create([
            'type' => SchoolContent::TYPE_TESTIMONIAL,
            'title' => 'Testimoni Pertama',
            'content' => 'Isi testimoni pertama.',
            'published_at' => now(),
            'is_published' => true,
            'sort_order' => 1,
        ]);

        SchoolContent::create([
            'type' => SchoolContent::TYPE_TESTIMONIAL,
            'title' => 'Testimoni Tersembunyi',
            'content' => 'Testimoni ini tidak boleh tampil.',
            'published_at' => now(),
            'is_published' => false,
            'sort_order' => 0,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSeeInOrder(['Testimoni Pertama', 'Testimoni Kedua'])
            ->assertDontSee('Testimoni Tersembunyi');
    }
}
