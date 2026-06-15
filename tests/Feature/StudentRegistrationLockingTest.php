<?php

namespace Tests\Feature;

use App\Models\PpdbFormPayment;
use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class StudentRegistrationLockingTest extends TestCase
{
    use RefreshDatabase;

    public function test_submitted_registration_opens_in_view_mode_with_edit_and_lock_actions(): void
    {
        [$user] = $this->createParentWithRegistration();

        $this->actingAs($user)
            ->get(route('data-diri'))
            ->assertOk()
            ->assertSee('Edit data')
            ->assertSee('Kunci pendaftaran')
            ->assertSee('Batal edit')
            ->assertDontSee('Data telah dikunci');
    }

    public function test_initial_submission_does_not_lock_registration_automatically(): void
    {
        config(['ppdb_notifications.enabled' => false]);
        Storage::fake('public');

        $user = User::factory()->create([
            'role' => User::ROLE_PARENT,
        ]);

        PpdbFormPayment::create([
            'user_id' => $user->id,
            'order_id' => 'FORM-TEST-' . $user->id,
            'amount' => 250000,
            'status' => 'settlement',
            'paid_at' => now(),
        ]);

        $payload = $this->registrationPayload();
        $payload['action'] = 'submit';
        $payload['agreement'] = '1';
        $payload['child_photo'] = UploadedFile::fake()->image('photo.png');
        $payload['parents_id_card'] = UploadedFile::fake()->image('parents.png');
        $payload['birth_certificate'] = UploadedFile::fake()->image('birth.png');
        $payload['family_card'] = UploadedFile::fake()->image('family.png');

        $this->actingAs($user)
            ->post(route('data-diri.update'), $payload)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('data-diri.success'));

        $registration = $user->studentRegistration()->firstOrFail();

        $this->assertNotNull($registration->submitted_at);
        $this->assertNull($registration->locked_at);
    }

    public function test_locked_registration_only_shows_permanent_locked_state(): void
    {
        [$user] = $this->createParentWithRegistration([
            'locked_at' => now(),
        ]);

        $this->actingAs($user)
            ->get(route('data-diri'))
            ->assertOk()
            ->assertSee('Data telah dikunci')
            ->assertDontSee('Edit data')
            ->assertDontSee('Batal edit');
    }

    public function test_locked_registration_cannot_be_changed_by_direct_request(): void
    {
        [$user, $registration] = $this->createParentWithRegistration([
            'locked_at' => now(),
        ]);

        $this->actingAs($user)
            ->post(route('data-diri.update'), [
                'action' => 'save',
                'nickname' => 'Nama Baru',
            ])
            ->assertRedirect(route('data-diri'))
            ->assertSessionHas('status');

        $this->assertSame('Dila', $registration->fresh()->nickname);
    }

    public function test_edit_mode_can_save_latest_values_and_lock_registration(): void
    {
        [$user, $registration] = $this->createParentWithRegistration();
        $payload = $this->registrationPayload();
        $payload['action'] = 'lock';
        $payload['nickname'] = 'Dila Baru';

        $this->actingAs($user)
            ->post(route('data-diri.update'), $payload)
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('data-diri'));

        $registration->refresh();

        $this->assertSame('Dila Baru', $registration->nickname);
        $this->assertNotNull($registration->locked_at);
    }

    protected function createParentWithRegistration(array $overrides = []): array
    {
        $user = User::factory()->create([
            'role' => User::ROLE_PARENT,
        ]);

        PpdbFormPayment::create([
            'user_id' => $user->id,
            'order_id' => 'FORM-TEST-' . $user->id,
            'amount' => 250000,
            'status' => 'settlement',
            'paid_at' => now(),
        ]);

        $registration = StudentRegistration::create(array_merge(
            $this->registrationModelData($user),
            $overrides,
        ));

        return [$user, $registration];
    }

    protected function registrationModelData(User $user): array
    {
        return array_merge($this->registrationPayload(), [
            'user_id' => $user->id,
            'special_needs' => false,
            'registration_number' => 'PPDB-TEST-' . $user->id,
            'submitted_at' => now(),
            'child_photo_path' => 'student-registrations/photo.png',
            'parents_id_card_path' => 'student-registrations/parents.png',
            'birth_certificate_path' => 'student-registrations/birth.png',
            'family_card_path' => 'student-registrations/family.png',
        ]);
    }

    protected function registrationPayload(): array
    {
        return [
            'full_name' => 'Ananda Fadhilah',
            'nickname' => 'Dila',
            'gender' => 'Perempuan',
            'birth_place' => 'Pekanbaru',
            'birth_date' => '2021-01-10',
            'religion' => 'Islam',
            'weight_kg' => 18.5,
            'height_cm' => 108.5,
            'home_address' => 'Jl. Muhajirin No. 10',
            'origin_region' => 'Pekanbaru',
            'citizenship' => 'WNI',
            'special_needs' => 'Tidak',
            'special_needs_description' => null,
            'child_status' => 'Kandung',
            'blood_type' => 'A',
            'child_order' => 1,
            'siblings_total' => 1,
            'medical_history' => null,
            'father_name' => 'Ayah Fadhilah',
            'father_birth_info' => 'Pekanbaru, 10 Januari 1990',
            'father_religion' => 'Islam',
            'father_citizenship' => 'WNI',
            'father_status' => 'Kandung',
            'father_job' => 'Wiraswasta',
            'father_education' => 'S1',
            'father_income' => 'Rp 5.000.001 - Rp 10.000.000',
            'father_phone' => '081234567890',
            'father_address' => 'Jl. Muhajirin No. 10',
            'mother_name' => 'Ibu Fadhilah',
            'mother_birth_info' => 'Pekanbaru, 11 Februari 1992',
            'mother_religion' => 'Islam',
            'mother_citizenship' => 'WNI',
            'mother_status' => 'Kandung',
            'mother_job' => 'Ibu Rumah Tangga',
            'mother_education' => 'S1',
            'mother_income' => 'Rp 1.000.000 - Rp 3.000.000',
            'mother_phone' => '081234567891',
            'mother_address' => 'Jl. Muhajirin No. 10',
        ];
    }
}
