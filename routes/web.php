<?php

use App\Http\Controllers\DashboardRedirectController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\HeadmasterDashboardController;
use App\Http\Controllers\PanitiaDashboardController;
use App\Http\Controllers\PanitiaFinanceController;
use App\Http\Controllers\PanitiaFormSaleController;
use App\Http\Controllers\PanitiaInterviewScheduleController;
use App\Http\Controllers\PanitiaRegistrationController;
use App\Http\Controllers\PanitiaSchoolContentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PpdbFormPaymentController;
use App\Http\Controllers\PublicPageController;
use App\Http\Controllers\StudentRegistrationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicPageController::class, 'home'])->name('home');
Route::post('/chatbot/message', [ChatbotController::class, 'message'])->name('chatbot.message');

// Halaman PPDB
Route::get('/ppdb/info', function () {
    return view('ppdb.link-pendaftaran');
})->name('ppdb.info');

Route::get('/ppdb/formulir', function () {
    return view('ppdb.persyaratan');
})->name('ppdb.formulir');

Route::get('/dashboard', DashboardRedirectController::class)->middleware(['auth'])->name('dashboard');
Route::get('/profile/dashboard', [PublicPageController::class, 'home'])->name('profile.dashboard');


Route::get('/profile/kata-sambutan', function () {
    return view('profile.katasambutan');
});

Route::get('/profile/sejarah', function () {
    return view('profile.sejarah');
});

Route::get('/profile/visi-misi', function () {
    return view('profile.visi-misi');
});

Route::get('/profile/tenaga-pendidik', [PublicPageController::class, 'teachers']);

Route::get('/profile/kontak-kami', function () {
    return view('profile.kontak-kami');
});

Route::get('/profile/fasilitas', [PublicPageController::class, 'facilities'])->name('profile.fasilitas');

Route::get('/profile/program-kegiatan-ra', function () {
    return view('profile.program-kegiatan-ra');
})->name('profile.program-kegiatan-ra');

Route::get('/blog/berita', function () {
    return view('blog.berita');
});

Route::get('/blog/kegiatan', [PublicPageController::class, 'activities'])->name('blog.kegiatan');
Route::get('/blog/prestasi', [PublicPageController::class, 'achievements'])->name('blog.prestasi');
Route::post('/midtrans/formulir/notification', [PpdbFormPaymentController::class, 'notification'])
    ->name('midtrans.formulir.notification');
Route::post('/midtrans/daftar-ulang/notification', [StudentRegistrationController::class, 'handleMidtransReRegistrationNotification'])
    ->name('midtrans.daftar-ulang.notification');

Route::middleware(['auth'])->group(function () {
    Route::get('/panel-ortu', function () {
        if (auth()->user()->isStaff()) {
            return redirect()->route('dashboard');
        }

        return view('dashboard.panel-ortu.home');
    })->name('panel.ortu');

    Route::get('/panel-ortu/formulir', [PpdbFormPaymentController::class, 'show'])->name('ortu.formulir');
    Route::post('/panel-ortu/formulir/midtrans/token', [PpdbFormPaymentController::class, 'createToken'])->name('ortu.formulir.midtrans.token');
    Route::post('/panel-ortu/formulir/midtrans/sync', [PpdbFormPaymentController::class, 'sync'])->name('ortu.formulir.midtrans.sync');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/data-diri', [StudentRegistrationController::class, 'edit'])->name('data-diri');
    Route::post('/data-diri', [StudentRegistrationController::class, 'update'])->name('data-diri.update');
    Route::get('/data-diri/sukses', [StudentRegistrationController::class, 'success'])->name('data-diri.success');
    Route::get('/persyaratan', [StudentRegistrationController::class, 'requirements'])->name('persyaratan');
    Route::post('/persyaratan/kunci', [StudentRegistrationController::class, 'lock'])->name('persyaratan.lock');
    Route::get('/wawancara', [StudentRegistrationController::class, 'interview'])->name('wawancara');
    Route::post('/wawancara', [StudentRegistrationController::class, 'storeInterview'])->name('wawancara.update');
    Route::get('/status-lulus', [StudentRegistrationController::class, 'graduationStatus'])->name('status-lulus');
    Route::post('/daftar-ulang/midtrans/token', [StudentRegistrationController::class, 'createReRegistrationPayment'])->name('daftar-ulang.midtrans.token');
    Route::post('/daftar-ulang/midtrans/sync', [StudentRegistrationController::class, 'syncReRegistrationPayment'])->name('daftar-ulang.midtrans.sync');
    Route::get('/data-diri/download/formulir', [StudentRegistrationController::class, 'downloadFormPdf'])->name('data-diri.download.formulir');
    Route::get('/data-diri/download/kartu-bukti', [StudentRegistrationController::class, 'downloadCardPdf'])->name('data-diri.download.kartu');
});

Route::middleware(['auth', 'role:panitia_ppdb,panitia'])->prefix('panitia')->name('panitia.')->group(function () {
    Route::get('/dashboard', [PanitiaDashboardController::class, 'index'])->name('dashboard');
    Route::post('/dashboard/jumlah-siswa-tahunan', [PanitiaDashboardController::class, 'updateAnnualStudentCounts'])->name('dashboard.annual-student-counts.update');
    Route::get('/pendaftaran', [PanitiaRegistrationController::class, 'index'])->name('registrations.index');
    Route::get('/pendaftaran/export', [PanitiaRegistrationController::class, 'export'])->name('registrations.export');
    Route::get('/pendaftaran/{registration}', [PanitiaRegistrationController::class, 'show'])->name('registrations.show');
    Route::put('/pendaftaran/{registration}', [PanitiaRegistrationController::class, 'update'])->name('registrations.update');
    Route::get('/formulir', [PanitiaFormSaleController::class, 'index'])->name('forms.index');
    Route::get('/formulir/export', [PanitiaFormSaleController::class, 'export'])->name('forms.export');
    Route::get('/wawancara', [PanitiaInterviewScheduleController::class, 'index'])->name('interviews.index');
    Route::get('/wawancara/export', [PanitiaInterviewScheduleController::class, 'export'])->name('interviews.export');
    Route::post('/wawancara/{registration}/jadwalkan', [PanitiaInterviewScheduleController::class, 'assign'])->name('interviews.assign');
    Route::get('/keuangan/bayar-formulir', [PanitiaFinanceController::class, 'formPayments'])->name('finances.form-payments.index');
    Route::get('/keuangan/bayar-formulir/export', [PanitiaFinanceController::class, 'exportFormPayments'])->name('finances.form-payments.export');
    Route::get('/keuangan/daftar-ulang', [PanitiaFinanceController::class, 'reRegistrations'])->name('finances.re-registrations.index');
    Route::get('/keuangan/daftar-ulang/export', [PanitiaFinanceController::class, 'exportReRegistrations'])->name('finances.re-registrations.export');

    Route::get('/konten', [PanitiaSchoolContentController::class, 'index'])->name('contents.index');
    Route::get('/konten/tambah', [PanitiaSchoolContentController::class, 'create'])->name('contents.create');
    Route::post('/konten', [PanitiaSchoolContentController::class, 'store'])->name('contents.store');
    Route::get('/konten/{content}/edit', [PanitiaSchoolContentController::class, 'edit'])->name('contents.edit');
    Route::put('/konten/{content}', [PanitiaSchoolContentController::class, 'update'])->name('contents.update');
    Route::delete('/konten/{content}', [PanitiaSchoolContentController::class, 'destroy'])->name('contents.destroy');
});

Route::middleware(['auth', 'role:kepsek'])->prefix('kepsek')->name('kepsek.')->group(function () {
    Route::get('/dashboard', [HeadmasterDashboardController::class, 'index'])->name('dashboard');
});

Route::get('/panel-admin', fn () => redirect()->route('panitia.dashboard'))
    ->middleware(['auth', 'role:panitia_ppdb,panitia'])
    ->name('panel.admin');


// Group route untuk profil (hanya untuk user yang login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/kata-sambutan', [ProfileController::class, 'index'])->name('profile.index');
    
});

require __DIR__.'/auth.php';
