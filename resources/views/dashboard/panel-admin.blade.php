<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard Admin PPDB</title>
<script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100">

<!-- HEADER -->
<header class="bg-gray-700 text-white flex justify-between items-center px-6 py-3">
    <div class="flex items-center gap-3">
        <span class="font-bold text-lg">PPDB</span>
        <span class="bg-blue-500 text-xs px-2 py-1 rounded">ONLINE</span>
    </div>

    <div class="flex items-center gap-3">
        <span class="text-sm">RAUDHATUL ATHFAL FADHILAH</span>
    </div>
</header>


<div class="flex">

<!-- SIDEBAR -->
<aside class="w-64 bg-white shadow-md min-h-screen hidden md:block">

<div class="bg-green-600 text-white p-4 font-semibold">
MENU DASHBOARD
</div>

<nav class="p-4 space-y-2 text-sm">

<a href="#" class="block p-2 hover:bg-gray-100 rounded">
🏠 Home
</a>

<a href="#" class="block p-2 hover:bg-gray-100 rounded">
✔ Verifikasi
</a>

<a href="#" class="block p-2 hover:bg-gray-100 rounded">
🎓 Kelulusan
</a>

<a href="#" class="block p-2 hover:bg-gray-100 rounded">
📊 Export Data
</a>

<hr>

<a href="#" class="block p-2 hover:bg-gray-100 rounded">
⚙ Pengaturan
</a>

<a href="#" class="block p-2 hover:bg-gray-100 rounded text-red-500">
⎋ Keluar
</a>

</nav>

</aside>


<!-- MAIN CONTENT -->
<main class="flex-1 p-6">

<!-- TITLE -->
<div class="bg-green-600 text-white p-4 rounded mb-6 font-semibold">
📊 DASHBOARD
</div>


<p class="mb-6 text-gray-700">
Selamat Datang, <b>RAUDHATUL ATHFAL FADHILAH</b>
</p>


<!-- STATISTICS -->
<div class="grid md:grid-cols-3 gap-6 mb-6">

<!-- JUMLAH PENDAFTAR -->
<div class="bg-teal-500 text-white p-6 rounded shadow">
<div class="text-3xl font-bold">2</div>
<div class="text-sm">JUMLAH PENDAFTAR</div>
</div>

<!-- LULUS -->
<div class="bg-orange-400 text-white p-6 rounded shadow">
<div class="text-3xl font-bold">0</div>
<div class="text-sm">TOTAL LULUS PPDB</div>
</div>

<!-- TIDAK LULUS -->
<div class="bg-green-500 text-white p-6 rounded shadow">
<div class="text-3xl font-bold">0</div>
<div class="text-sm">TOTAL TIDAK LULUS PPDB 2026</div>
</div>

</div>


<!-- STATUS PENDAFTARAN -->
<div class="bg-blue-100 border border-blue-300 p-4 rounded flex flex-col md:flex-row md:items-center md:justify-between gap-3">

<button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
Tutup Pendaftaran PPDB Online
</button>

<p class="text-sm text-gray-700">
Status Pendaftaran PPDB Online masih dibuka. 
<span class="text-gray-500 text-xs">Terakhir diubah 17-04-2021 00:22:09</span>
</p>

</div>


</main>
</div>


<!-- FOOTER -->
<footer class="bg-teal-800 text-white text-center py-3 text-sm">
Copyright © 2026 RAUDHATUL ATHFAL FADHILAH
</footer>


</body>
</html>