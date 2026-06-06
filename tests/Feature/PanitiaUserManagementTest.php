<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PanitiaUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_panitia_can_open_user_management_page(): void
    {
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);

        $this->actingAs($panitia)
            ->get(route('panitia.users.index'))
            ->assertOk()
            ->assertSee('Manajemen User');
    }

    public function test_panitia_can_create_user_with_selected_role(): void
    {
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);

        $response = $this->actingAs($panitia)->post(route('panitia.users.store'), [
            'name' => 'Petugas Baru',
            'username' => 'PetugasBaru',
            'email' => 'petugas@example.com',
            'role' => User::ROLE_PRINCIPAL,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect(route('panitia.users.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'PetugasBaru',
            'role' => User::ROLE_PRINCIPAL,
        ]);
    }

    public function test_parent_cannot_access_panitia_user_management(): void
    {
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);

        $this->actingAs($parent)
            ->get(route('panitia.users.index'))
            ->assertForbidden();
    }

    public function test_panitia_can_update_another_user(): void
    {
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);

        $this->actingAs($panitia)
            ->put(route('panitia.users.update', $parent), [
                'name' => 'Orang Tua Diperbarui',
                'username' => 'orangtuabaru',
                'email' => 'orangtua.baru@example.com',
                'role' => User::ROLE_PRINCIPAL,
                'password' => '',
                'password_confirmation' => '',
            ])
            ->assertRedirect(route('panitia.users.index'));

        $this->assertDatabaseHas('users', [
            'id' => $parent->id,
            'name' => 'Orang Tua Diperbarui',
            'role' => User::ROLE_PRINCIPAL,
        ]);
    }

    public function test_panitia_can_delete_another_user(): void
    {
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);

        $this->actingAs($panitia)
            ->delete(route('panitia.users.destroy', $parent))
            ->assertRedirect(route('panitia.users.index'));

        $this->assertDatabaseMissing('users', ['id' => $parent->id]);
    }

    public function test_panitia_cannot_delete_their_own_account(): void
    {
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);

        $this->actingAs($panitia)
            ->delete(route('panitia.users.destroy', $panitia))
            ->assertSessionHasErrors('user');

        $this->assertDatabaseHas('users', ['id' => $panitia->id]);
    }
}
