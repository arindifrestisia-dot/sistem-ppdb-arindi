<?php

namespace Tests\Feature;

use App\Models\ParentFormField;
use App\Models\PpdbFormPayment;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ParentFormFieldManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_panitia_can_manage_parent_form_fields(): void
    {
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);

        $this->actingAs($panitia)
            ->post(route('panitia.parent-form-fields.store'), [
                'label' => 'Sekolah Asal Anak',
                'type' => 'text',
                'section' => 'child',
                'placeholder' => 'Nama sekolah sebelumnya',
                'help_text' => 'Kosongkan jika belum pernah sekolah.',
                'sort_order' => 10,
                'is_required' => '1',
                'is_active' => '1',
            ])
            ->assertRedirect(route('panitia.parent-form-fields.index'));

        $field = ParentFormField::firstOrFail();

        $this->assertSame('sekolah_asal_anak', $field->field_key);
        $this->assertTrue($field->is_required);

        $this->actingAs($panitia)
            ->delete(route('panitia.parent-form-fields.destroy', $field))
            ->assertRedirect(route('panitia.parent-form-fields.index'));

        $this->assertDatabaseMissing('parent_form_fields', ['id' => $field->id]);
    }

    public function test_active_custom_field_appears_on_parent_registration_form(): void
    {
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);

        PpdbFormPayment::create([
            'user_id' => $parent->id,
            'order_id' => 'FORM-CUSTOM-FIELD',
            'amount' => 150000,
            'status' => 'settlement',
            'paid_at' => now(),
        ]);

        ParentFormField::create([
            'label' => 'Sekolah Asal Anak',
            'field_key' => 'sekolah_asal_anak',
            'type' => 'text',
            'section' => 'child',
            'is_required' => true,
            'is_active' => true,
            'sort_order' => 10,
        ]);

        $this->actingAs($parent)
            ->get(route('data-diri'))
            ->assertOk()
            ->assertSee('Sekolah Asal Anak')
            ->assertSee('custom_fields[sekolah_asal_anak]', false);
    }
}
