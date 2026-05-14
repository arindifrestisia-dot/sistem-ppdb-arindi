<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Orang Tua</title>

<script src="https://cdn.tailwindcss.com"></script>

</head>

<body class="bg-gray-100">

<div class="flex min-h-screen">

<!-- SIDEBAR -->
<aside class="w-64 bg-blue-900 text-white hidden md:block">

<div class="p-5 border-b border-blue-700">
<h1 class="font-bold text-lg">PPDB RA FADHILAH</h1>
<p class="text-xs">(Penerimaan Murid Baru)</p>
</div>

<div class="p-4 text-center border-b border-blue-700">
<div class="w-16 h-16 bg-gray-300 rounded-full mx-auto mb-2"></div>
<p class="text-sm font-semibold">Arindi Frestisia Ningtias</p>
<span class="bg-green-500 text-xs px-2 py-1 rounded">USER</span>
</div>

<nav class="mt-4 text-sm">

<a href="#" class="block px-6 py-3 bg-green-500">
🏠 Beranda
</a>

<a href="#" class="block px-6 py-3 hover:bg-blue-700">
👤 Data Diri
</a>

<a href="#" class="block px-6 py-3 hover:bg-blue-700">
📄 Persyaratan
</a>

<a href="#" class="block px-6 py-3 hover:bg-blue-700">
🎓 Status Lulus
</a>

</nav>

</aside>


<!-- MAIN CONTENT -->
<div class="flex-1 flex flex-col">

<!-- HEADER -->
<header class="bg-white shadow px-6 py-3 flex justify-between items-center">

<div class="text-gray-700 font-semibold">
Selamat Datang, Arindi Frestisia Ningtias!
</div>

<a href="#" class="text-red-500 text-sm">
Keluar
</a>

</header>


<!-- CONTENT -->
<div class="p-6">

<p class="text-gray-500 mb-4">
Berikut adalah ringkasan status pendaftaran Anda saat ini.
</p>


<!-- STATUS CARDS -->
<div class="grid md:grid-cols-3 gap-4 mb-6">

<div class="bg-yellow-400 p-4 rounded shadow">
<h2 class="text-2xl font-bold">Belum</h2>
<p class="text-sm">Data Identitas Siswa</p>
<button class="text-sm mt-2 underline">Kelola Data ➜</button>
</div>

<div class="bg-yellow-400 p-4 rounded shadow">
<h2 class="text-2xl font-bold">Belum</h2>
<p class="text-sm">Data Orang Tua / Wali</p>
<button class="text-sm mt-2 underline">Kelola Data ➜</button>
</div>

<div class="bg-yellow-400 p-4 rounded shadow">
<h2 class="text-2xl font-bold">Belum</h2>
<p class="text-sm">Data Sekolah Asal</p>
<button class="text-sm mt-2 underline">Kelola Data ➜</button>
</div>

</div>


<!-- MAIN GRID -->
<div class="grid md:grid-cols-3 gap-6">

<!-- PROFIL -->
<div class="md:col-span-2 bg-white rounded shadow p-6">

<h3 class="font-semibold mb-4 border-b pb-2">
Ringkasan Profil Anda
</h3>

<div class="text-center py-10">

<div class="text-5xl mb-4">👤</div>

<p class="text-gray-500 mb-4">
Anda belum mengisi data diri secara lengkap.
</p>

<button class="bg-green-500 text-white px-5 py-2 rounded hover:bg-green-600">
Mulai Isi Data Sekarang
</button>

</div>

</div>


<!-- LANGKAH -->
<div class="bg-cyan-500 text-white rounded shadow p-6">

<h3 class="font-semibold mb-4">
Langkah Selanjutnya
</h3>

<ol class="space-y-2 text-sm">

<li>1. Isi Data Diri Lengkap</li>
<li>2. Pilih Program Kelas</li>
<li>3. Upload Berkas Persyaratan</li>
<li>4. Simpan Permanen</li>

</ol>

</div>

</div>


</div>


<!-- FOOTER -->
<footer class="bg-gray-200 text-center text-sm py-3 mt-auto">
Copyright © 2026 PPDB Online
</footer>


</div>
</div>

</body>
</html>