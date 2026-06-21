<?php

namespace Tests\Feature;

use App\Models\HomeBanner;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PanitiaBannerManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_panitia_can_upload_replace_and_delete_a_home_banner(): void
    {
        Storage::fake('public');
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);

        $this->actingAs($panitia)
            ->put(route('panitia.banners.update', 1), [
                'image' => UploadedFile::fake()->image('banner-lama.jpg', 1600, 700),
            ])
            ->assertRedirect();

        $banner = HomeBanner::where('slot', 1)->firstOrFail();
        $oldPath = $banner->image_path;
        Storage::disk('public')->assertExists($oldPath);

        $this->actingAs($panitia)
            ->put(route('panitia.banners.update', 1), [
                'image' => UploadedFile::fake()->image('banner-baru.png', 1600, 700),
            ])
            ->assertRedirect();

        $banner->refresh();
        Storage::disk('public')->assertMissing($oldPath);
        Storage::disk('public')->assertExists($banner->image_path);

        $currentPath = $banner->image_path;
        $this->actingAs($panitia)
            ->delete(route('panitia.banners.destroy', 1))
            ->assertRedirect();

        $this->assertDatabaseMissing('home_banners', ['slot' => 1]);
        Storage::disk('public')->assertMissing($currentPath);
    }

    public function test_uploaded_banners_are_used_on_public_homepage(): void
    {
        HomeBanner::create(['slot' => 2, 'image_path' => 'home-banners/banner-profil-baru.jpg']);

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('banner-profil-baru.jpg');
    }

    public function test_parent_cannot_manage_banners(): void
    {
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);

        $this->actingAs($parent)
            ->get(route('panitia.banners.index'))
            ->assertForbidden();
    }
}
