<?php

namespace Tests\Feature;

use App\Models\PpdbFormPayment;
use App\Models\StudentRegistration;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ManualPaymentVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_parent_can_submit_transfer_proof_and_panitia_can_verify_form_payment(): void
    {
        Storage::fake('public');
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);

        $this->actingAs($parent)->post(route('ortu.formulir.manual'), [
            'payment_method' => 'transfer',
            'proof' => UploadedFile::fake()->image('bukti-transfer.jpg'),
        ])->assertRedirect();

        $payment = PpdbFormPayment::firstOrFail();
        $this->assertSame('manual_pending', $payment->status);
        $this->assertSame('manual_transfer', $payment->payment_type);
        Storage::disk('public')->assertExists($payment->proof_path);
        $this->assertFalse($parent->fresh()->hasPaidPpdbForm());

        $this->actingAs($parent)->get(route('ortu.formulir'))
            ->assertOk()
            ->assertSee('Menunggu Verifikasi Panitia')
            ->assertSee('Bukti pembayaran berhasil diunggah')
            ->assertSee('Pembayaran formulir Anda sedang dalam proses verifikasi oleh panitia. Mohon menunggu hingga proses verifikasi selesai.')
            ->assertDontSee('Pembayaran Manual')
            ->assertDontSee('Kirim untuk Verifikasi')
            ->assertDontSee('name="payment_method"', false)
            ->assertDontSee('id="payFormButton"', false);

        $this->actingAs($parent)->post(route('ortu.formulir.manual'), [
            'payment_method' => 'cash',
        ])->assertRedirect();
        $this->assertSame('manual_transfer', $payment->fresh()->payment_type);

        $this->actingAs($parent)
            ->get(route('panitia.finances.form-payments.proof', $payment))
            ->assertForbidden();
        $this->actingAs($panitia)
            ->get(route('panitia.finances.form-payments.proof', $payment))
            ->assertOk();

        $this->actingAs($panitia)
            ->patch(route('panitia.finances.form-payments.verify', $payment))
            ->assertRedirect();

        $payment->refresh();
        $this->assertTrue($payment->isPaid());
        $this->assertSame($panitia->id, $payment->verified_by);
        $this->assertTrue($parent->fresh()->hasPaidPpdbForm());

        $this->actingAs($parent)->get(route('ortu.formulir'))
            ->assertOk()
            ->assertSee('Pembayaran Formulir Berhasil Diverifikasi')
            ->assertSee('Pembayaran formulir Anda telah diverifikasi oleh panitia. Silakan klik tombol di bawah untuk melanjutkan pengisian formulir pendaftaran peserta didik baru.')
            ->assertSee('Pembayaran Terverifikasi')
            ->assertSee('Buka Formulir Pendaftaran')
            ->assertSee(route('data-diri'), false);
    }

    public function test_cash_form_payment_can_be_submitted_without_proof(): void
    {
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);

        $this->actingAs($parent)->post(route('ortu.formulir.manual'), [
            'payment_method' => 'cash',
        ])->assertRedirect();

        $this->assertDatabaseHas('ppdb_form_payments', [
            'user_id' => $parent->id,
            'payment_type' => 'manual_cash',
            'status' => 'manual_pending',
            'proof_path' => null,
        ]);
    }

    public function test_pending_form_payment_is_visible_from_candidate_student_list(): void
    {
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);

        $registration = StudentRegistration::create([
            'user_id' => $parent->id,
            'full_name' => 'Nabila Fadhilah',
            'nickname' => 'Nabila',
            'gender' => 'Perempuan',
            'birth_place' => 'Pekanbaru',
            'birth_date' => now()->subYears(5)->toDateString(),
            'weight_kg' => 18,
            'height_cm' => 105,
            'home_address' => 'Pekanbaru',
            'origin_region' => 'Pekanbaru',
            'citizenship' => 'WNI',
            'special_needs' => false,
            'child_order' => 1,
            'siblings_total' => 0,
            'registration_number' => 'PPDB-2026-90001',
            'submitted_at' => now(),
        ]);

        PpdbFormPayment::create([
            'user_id' => $parent->id,
            'order_id' => 'FORM-PENDING-90001',
            'amount' => 150000,
            'status' => 'manual_pending',
            'payment_type' => 'manual_transfer',
        ]);

        $this->actingAs($panitia)
            ->get(route('panitia.registrations.index', ['segment' => 'calon']))
            ->assertOk()
            ->assertSee($registration->full_name)
            ->assertSee('Formulir perlu verifikasi')
            ->assertSee('Transfer BRI / DANA')
            ->assertSee('Cek pembayaran')
            ->assertSee('FORM-PENDING-90001', false)
            ->assertSee('status=menunggu', false);
    }

    public function test_pending_reregistration_payment_is_visible_from_registration_list(): void
    {
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);

        $registration = StudentRegistration::create([
            'user_id' => $parent->id,
            'full_name' => 'Rafi Fadhilah',
            'nickname' => 'Rafi',
            'gender' => 'Laki-laki',
            'birth_place' => 'Pekanbaru',
            'birth_date' => now()->subYears(5)->toDateString(),
            'weight_kg' => 18,
            'height_cm' => 105,
            'home_address' => 'Pekanbaru',
            'origin_region' => 'Pekanbaru',
            'citizenship' => 'WNI',
            'special_needs' => false,
            'child_order' => 1,
            'siblings_total' => 0,
            'registration_number' => 'PPDB-2026-90002',
            'submitted_at' => now(),
            'selection_result' => 'lulus',
            'selection_published_at' => now(),
            'reregistration_status' => 'manual_pending',
            'reregistration_payment_type' => 'manual_cash',
        ]);

        $this->actingAs($panitia)
            ->get(route('panitia.registrations.index', ['segment' => 'daftar_ulang']))
            ->assertOk()
            ->assertSee($registration->full_name)
            ->assertSee('Daftar ulang perlu verifikasi')
            ->assertSee('Cash ke sekolah')
            ->assertSee('Cek pembayaran')
            ->assertSee('status=belum_lunas', false)
            ->assertSee('PPDB-2026-90002', false);
    }

    public function test_panitia_cannot_manually_verify_a_midtrans_payment(): void
    {
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);
        $payment = PpdbFormPayment::create([
            'user_id' => $parent->id,
            'order_id' => 'FORM-MIDTRANS-TEST',
            'amount' => 150000,
            'status' => 'pending',
            'payment_type' => 'midtrans',
        ]);

        $this->actingAs($panitia)
            ->patch(route('panitia.finances.form-payments.verify', $payment))
            ->assertStatus(422);

        $this->assertFalse($payment->fresh()->isPaid());
    }

    public function test_parent_can_submit_reregistration_proof_and_panitia_can_verify_it(): void
    {
        Storage::fake('public');
        $parent = User::factory()->create(['role' => User::ROLE_PARENT]);
        $panitia = User::factory()->create(['role' => User::ROLE_COMMITTEE]);
        $registration = StudentRegistration::create([
            'user_id' => $parent->id,
            'full_name' => 'Ananda Fadhilah',
            'nickname' => 'Ananda',
            'gender' => 'perempuan',
            'birth_place' => 'Pekanbaru',
            'birth_date' => now()->subYears(5)->toDateString(),
            'weight_kg' => 18,
            'height_cm' => 105,
            'home_address' => 'Pekanbaru',
            'origin_region' => 'Pekanbaru',
            'citizenship' => 'WNI',
            'special_needs' => false,
            'child_order' => 1,
            'siblings_total' => 0,
            'selection_result' => 'lulus',
            'selection_published_at' => now(),
        ]);

        $this->actingAs($parent)->post(route('daftar-ulang.manual'), [
            'payment_plan' => 'full',
            'payment_method' => 'transfer',
            'proof' => UploadedFile::fake()->image('bukti-daftar-ulang.png'),
        ])->assertRedirect();

        $registration->refresh();
        $this->assertSame('manual_pending', $registration->reregistration_status);
        Storage::disk('public')->assertExists($registration->reregistration_proof_path);

        $this->actingAs($parent)->get(route('daftar-ulang'))
            ->assertOk()
            ->assertSee('Menunggu Verifikasi Panitia')
            ->assertDontSee('Kirim untuk Verifikasi')
            ->assertDontSee('name="payment_method"', false)
            ->assertDontSee('id="payReregistrationButton"', false);

        $this->actingAs($panitia)
            ->patch(route('panitia.finances.re-registrations.verify', $registration))
            ->assertRedirect();

        $registration->refresh();
        $this->assertSame('settlement', $registration->reregistration_status);
        $this->assertNotNull($registration->reregistration_paid_at);
        $this->assertSame($panitia->id, $registration->reregistration_verified_by);
    }
}
