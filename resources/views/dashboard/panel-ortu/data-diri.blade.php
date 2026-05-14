<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Formulir Pendaftaran</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
<div class="flex min-h-screen">
<aside class="hidden w-64 bg-blue-900 text-white md:block">
<div class="border-b border-blue-700 p-5">
<h1 class="text-lg font-bold">PMBM MAN 1</h1>
<p class="text-xs">(Penerimaan Murid Baru Madrasah)</p>
</div>

<div class="border-b border-blue-700 p-4 text-center">
<div class="mx-auto mb-2 h-16 w-16 rounded-full bg-gray-300"></div>
<p class="text-sm font-semibold">Rizqy Syuhada Alfajri</p>
<span class="rounded bg-green-500 px-2 py-1 text-xs">USER</span>
</div>

<nav class="mt-4 text-sm">
<a href="#" class="block px-6 py-3 hover:bg-blue-700">Beranda</a>
<a href="#" class="block bg-green-500 px-6 py-3">Formulir Pendaftaran</a>
<a href="#" class="block px-6 py-3 hover:bg-blue-700">Persyaratan</a>
<a href="#" class="block px-6 py-3 hover:bg-blue-700">Status Lulus</a>
</nav>
</aside>

<div class="flex-1">
<header class="flex justify-between bg-white px-6 py-3 shadow">
<h2 class="font-semibold">Formulir Pendaftaran</h2>
</header>

<div class="p-6">
<p class="mb-4 text-gray-500">
Lengkapi data anak dan upload berkas yang dibutuhkan sebelum melanjutkan ke tahap berikutnya.
</p>

<div class="mb-6 flex flex-wrap gap-2">
<div class="rounded bg-green-500 px-4 py-2 text-sm text-white">1. DATA ANAK</div>
<div class="rounded bg-gray-200 px-4 py-2 text-sm">2. DATA ORANG TUA / WALI</div>
<div class="rounded bg-gray-200 px-4 py-2 text-sm">3. UPLOAD BERKAS</div>
</div>

<form class="space-y-8 rounded bg-white p-6 shadow">
<section class="space-y-4">
<h3 class="font-semibold text-green-700">A. DATA ANAK</h3>

<div class="grid gap-4 md:grid-cols-2">
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Nama Lengkap</label>
<input type="text" class="w-full rounded border p-2" placeholder="Masukkan nama lengkap anak">
</div>
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Nama Panggilan</label>
<input type="text" class="w-full rounded border p-2" placeholder="Masukkan nama panggilan">
</div>
</div>

<div class="grid gap-4 md:grid-cols-3">
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Jenis Kelamin</label>
<select class="w-full rounded border p-2">
<option value="">Pilih jenis kelamin</option>
<option>Laki-laki</option>
<option>Perempuan</option>
</select>
</div>
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Tempat Lahir</label>
<input type="text" class="w-full rounded border p-2" placeholder="Masukkan tempat lahir">
</div>
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Tanggal Lahir</label>
<input type="date" class="w-full rounded border p-2">
</div>
</div>

<div class="grid gap-4 md:grid-cols-2">
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Berat Badan (kg)</label>
<input type="number" class="w-full rounded border p-2" placeholder="Contoh: 18">
</div>
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Tinggi Badan (cm)</label>
<input type="number" class="w-full rounded border p-2" placeholder="Contoh: 105">
</div>
</div>

<div class="grid gap-4 md:grid-cols-2">
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Alamat Rumah</label>
<textarea class="min-h-[110px] w-full rounded border p-2" placeholder="Masukkan alamat rumah lengkap"></textarea>
</div>
<div class="space-y-4">
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Asal Daerah</label>
<input type="text" class="w-full rounded border p-2" placeholder="Masukkan asal daerah">
</div>
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Kewarganegaraan</label>
<select class="w-full rounded border p-2">
<option value="">Pilih kewarganegaraan</option>
<option>WNI</option>
<option>WNA</option>
</select>
</div>
</div>
</div>

<div class="grid gap-4 md:grid-cols-3">
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Berkebutuhan Khusus</label>
<select class="w-full rounded border p-2">
<option value="">Pilih opsi</option>
<option>Ya</option>
<option>Tidak</option>
</select>
</div>
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Anak ke-</label>
<input type="number" class="w-full rounded border p-2" placeholder="Contoh: 2">
</div>
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Dari Total Anak</label>
<input type="number" class="w-full rounded border p-2" placeholder="Contoh: 3">
</div>
</div>

<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Penyakit Bawaan yang Pernah Diderita</label>
<textarea class="min-h-[110px] w-full rounded border p-2" placeholder="Kosongkan jika tidak ada"></textarea>
</div>
</section>

<section class="space-y-4">
<h3 class="font-semibold text-green-700">B. UPLOAD BERKAS</h3>

<div class="grid gap-4 md:grid-cols-2">
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Pas Foto 3x4 Anak</label>
<input type="file" class="file-upload w-full rounded border p-2" accept=".jpg,.jpeg,.png,.pdf">
<p class="file-name-preview mt-2 text-sm text-gray-500">Belum ada file dipilih</p>
</div>
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Scan KTP Kedua Orang Tua</label>
<input type="file" class="file-upload w-full rounded border p-2" accept=".jpg,.jpeg,.png,.pdf">
<p class="file-name-preview mt-2 text-sm text-gray-500">Belum ada file dipilih</p>
</div>
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Akte Lahir Anak</label>
<input type="file" class="file-upload w-full rounded border p-2" accept=".jpg,.jpeg,.png,.pdf">
<p class="file-name-preview mt-2 text-sm text-gray-500">Belum ada file dipilih</p>
</div>
<div>
<label class="mb-1 block text-sm font-medium text-gray-700">Kartu Keluarga</label>
<input type="file" class="file-upload w-full rounded border p-2" accept=".jpg,.jpeg,.png,.pdf">
<p class="file-name-preview mt-2 text-sm text-gray-500">Belum ada file dipilih</p>
</div>
</div>
</section>

<div class="pt-2">
<button class="rounded bg-green-600 px-6 py-2 text-white hover:bg-green-700">
Simpan Data
</button>
</div>
</form>
</div>
</div>
</div>
<script>
document.querySelectorAll('.file-upload').forEach((input) => {
    input.addEventListener('change', () => {
        const preview = input.parentElement.querySelector('.file-name-preview');

        if (!preview) {
            return;
        }

        preview.textContent = input.files && input.files.length > 0
            ? input.files[0].name
            : 'Belum ada file dipilih';
    });
});
</script>
</body>
</html>
