<?php

namespace Tests\Feature;

use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentRegistrationInterviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_submitted_parent_can_view_interview_schedule_without_locking_registration(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_PARENT,
        ]);

        $this->createSubmittedRegistration($user, [
            'locked_at' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('wawancara'));

        $response
            ->assertOk()
            ->assertViewHas('isInterviewAvailable', true)
            ->assertSee('Pilih Tanggal Wawancara')
            ->assertDontSee('Sesi 1');
    }

    public function test_submitted_parent_can_save_interview_schedule_without_locking_registration(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_PARENT,
        ]);

        $registration = $this->createSubmittedRegistration($user, [
            'locked_at' => null,
        ]);

        $response = $this
            ->actingAs($user)
            ->post(route('wawancara.update'), [
                'interview_schedule_key' => '2026-07-01',
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('wawancara'));

        $registration->refresh();

        $this->assertSame('2026-07-01', $registration->interview_schedule_key);
        $this->assertSame('Silahkan datang ke sekolah RA FADHILAH pada jam 08.00 - 13.00', $registration->interview_time);
        $this->assertSame('RUANGAN TU', $registration->interview_room);
        $this->assertNotNull($registration->interview_selected_at);
        $this->assertNull($registration->locked_at);
    }

    public function test_parent_cannot_change_interview_schedule_after_it_has_been_selected(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_PARENT,
        ]);

        $registration = $this->createSubmittedRegistration($user, [
            'locked_at' => null,
            'interview_schedule_key' => '2026-07-01',
            'interview_date' => '2026-07-01',
            'interview_day_name' => 'Rabu',
            'interview_time' => '08.00 - 08.30 WIB',
            'interview_room' => 'Ruang Wawancara A',
            'interview_selected_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->from(route('wawancara'))
            ->post(route('wawancara.update'), [
                'interview_schedule_key' => '2026-07-02',
            ]);

        $response
            ->assertRedirect(route('wawancara'))
            ->assertSessionHasErrors([
                'interview_schedule_key' => 'Jadwal wawancara sudah dipilih dan tidak dapat diubah lagi.',
            ]);

        $registration->refresh();

        $this->assertSame('2026-07-01', $registration->interview_schedule_key);
        $this->assertSame('08.00 - 08.30 WIB', $registration->interview_time);
    }

    public function test_selected_interview_schedule_hides_other_session_choices(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_PARENT,
        ]);

        $this->createSubmittedRegistration($user, [
            'locked_at' => null,
            'interview_schedule_key' => '2026-07-01',
            'interview_date' => '2026-07-01',
            'interview_day_name' => 'Rabu',
            'interview_time' => '08.00 - 08.30 WIB',
            'interview_room' => 'Ruang Wawancara A',
            'interview_selected_at' => now(),
        ]);

        $response = $this
            ->actingAs($user)
            ->get(route('wawancara'));

        $response
            ->assertOk()
            ->assertSee('Jadwal Terpilih')
            ->assertSee('08.00 - 08.30 WIB')
            ->assertSee('Ruang Wawancara A')
            ->assertSee('Cetak Kartu Bukti')
            ->assertDontSee('Simpan Jadwal Wawancara');
    }

    public function test_panitia_can_save_interview_result_notes_for_each_registration(): void
    {
        $parent = User::factory()->create([
            'role' => User::ROLE_PARENT,
        ]);
        $panitia = User::factory()->create([
            'role' => User::ROLE_COMMITTEE,
        ]);

        $registration = $this->createSubmittedRegistration($parent, [
            'interview_schedule_key' => '2026-07-01',
            'interview_date' => '2026-07-01',
            'interview_day_name' => 'Rabu',
            'interview_time' => 'Silahkan datang ke sekolah RA FADHILAH pada jam 08.00 - 13.00',
            'interview_room' => 'RUANGAN TU',
            'interview_selected_at' => now(),
        ]);

        $this->actingAs($panitia)
            ->get(route('panitia.interviews.index'))
            ->assertOk()
            ->assertSee('Catatan hasil wawancara');

        $this->actingAs($panitia)
            ->patch(route('panitia.interviews.status.update', $registration), [
                'interview_status' => 'simpan_catatan',
                'interview_notes' => 'Ananda sudah komunikatif dan siap mengikuti kegiatan kelas.',
            ])
            ->assertRedirect();

        $this->assertSame(
            'Ananda sudah komunikatif dan siap mengikuti kegiatan kelas.',
            $registration->fresh()->interview_notes
        );

        $this->actingAs($panitia)
            ->get(route('panitia.interviews.index'))
            ->assertOk()
            ->assertSee('Ananda sudah komunikatif dan siap mengikuti kegiatan kelas.')
            ->assertSee('Catatan sudah disimpan dan tidak dapat diubah lagi.')
            ->assertDontSee('Tandai Selesai Wawancara');

        $this->actingAs($panitia)
            ->patch(route('panitia.interviews.status.update', $registration), [
                'interview_status' => 'simpan_catatan',
                'interview_notes' => 'Catatan ini tidak boleh mengganti catatan lama.',
            ])
            ->assertSessionHasErrors('interview_status');

        $this->assertSame(
            'Ananda sudah komunikatif dan siap mengikuti kegiatan kelas.',
            $registration->fresh()->interview_notes
        );
    }

    protected function createSubmittedRegistration(User $user, array $overrides = []): StudentRegistration
    {
        return StudentRegistration::create(array_merge([
            'user_id' => $user->id,
            'full_name' => 'Ananda Fadhilah',
            'nickname' => 'Dila',
            'gender' => 'Perempuan',
            'birth_place' => 'Pekanbaru',
            'birth_date' => '2021-01-10',
            'weight_kg' => 18.50,
            'height_cm' => 108.50,
            'home_address' => 'Jl. Muhajirin No. 10',
            'origin_region' => 'Pekanbaru',
            'citizenship' => 'WNI',
            'special_needs' => false,
            'child_order' => 1,
            'siblings_total' => 1,
            'medical_history' => null,
            'father_name' => 'Ayah Fadhilah',
            'father_birth_info' => 'Pekanbaru, 10 Januari 1990',
            'father_job' => 'Wiraswasta',
            'father_education' => 'S1',
            'father_income' => 'Rp 5.000.001 - Rp 10.000.000',
            'father_phone' => '081234567890',
            'mother_name' => 'Ibu Fadhilah',
            'mother_birth_info' => 'Pekanbaru, 11 Februari 1992',
            'mother_job' => 'Ibu Rumah Tangga',
            'mother_education' => 'S1',
            'mother_income' => 'Rp 1.000.000 - Rp 3.000.000',
            'mother_phone' => '081234567891',
            'registration_number' => 'PPDB-TEST-00001',
            'submitted_at' => now(),
        ], $overrides));
    }
}
