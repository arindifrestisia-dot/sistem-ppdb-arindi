<?php

namespace Tests\Feature;

use App\Models\SchoolContent;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TeacherProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_panitia_can_store_teacher_nip(): void
    {
        Storage::fake('public');
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);

        $this->actingAs($panitia)
            ->post(route('panitia.contents.store'), [
                'type' => SchoolContent::TYPE_TEACHER,
                'title' => 'Bunda Anda Sri Dewi, SE',
                'excerpt' => 'Guru Kelas A',
                'content' => '19850101 201001 2 001',
                'images' => [UploadedFile::fake()->image('guru.jpg')],
            ])
            ->assertRedirect(route('panitia.contents.index', ['type' => SchoolContent::TYPE_TEACHER]));

        $this->assertDatabaseHas('school_contents', [
            'type' => SchoolContent::TYPE_TEACHER,
            'title' => 'Bunda Anda Sri Dewi, SE',
            'excerpt' => 'Guru Kelas A',
            'content' => '19850101 201001 2 001',
        ]);
    }

    public function test_published_teacher_profile_can_be_opened(): void
    {
        $teacher = SchoolContent::create([
            'type' => SchoolContent::TYPE_TEACHER,
            'title' => 'Bunda Anda Sri Dewi, SE',
            'excerpt' => 'Guru Kelas A',
            'content' => '19850101 201001 2 001',
            'published_at' => now(),
            'is_published' => true,
        ]);

        $this->get(route('profile.tenaga-pendidik.show', $teacher))
            ->assertOk()
            ->assertSee('Bunda Anda Sri Dewi, SE')
            ->assertSee('19850101 201001 2 001')
            ->assertSee('Guru Kelas A');
    }

    public function test_non_teacher_content_cannot_be_opened_as_teacher_profile(): void
    {
        $content = SchoolContent::create([
            'type' => SchoolContent::TYPE_INFORMATION,
            'title' => 'Berita Sekolah',
            'is_published' => true,
        ]);

        $this->get(route('profile.tenaga-pendidik.show', $content))
            ->assertNotFound();
    }

    public function test_home_links_teacher_photo_to_profile(): void
    {
        $teacher = SchoolContent::create([
            'type' => SchoolContent::TYPE_TEACHER,
            'title' => 'Bunda Anda Sri Dewi, SE',
            'excerpt' => 'Guru Kelas A',
            'content' => '19850101 201001 2 001',
            'published_at' => now(),
            'is_published' => true,
        ]);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee(route('profile.tenaga-pendidik.show', $teacher), false);
    }
}
