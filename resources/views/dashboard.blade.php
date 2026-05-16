@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <!-- Header Banner -->
    <div class="bg-gradient-to-r from-pink-200 via-purple-100 to-blue-200 w-full">
    <div class="max-w-8xl mx-auto py-16 px-8 text-center">
        <h1 class="text-5xl md:text-6xl font-extrabold text-blue-900 mb-4 drop-shadow-md">
            Selamat Datang!
        </h1>
        <p class="text-2xl md:text-3xl font-semibold text-gray-800 mb-2">
            Siswa dan Siswi Taman Kanak-Kanak <span class="text-pink-600">RA Fadhilah</span>
        </p>
        <p class="text-xl md:text-2xl font-medium text-gray-700 mb-6">
            Tahun Pelajaran <span class="text-blue-800">2026 / 2027</span>
        </p>
        <a href="#pendaftaran" 
           class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-semibold text-lg px-8 py-3 rounded-full shadow-md transition duration-300">
            Daftar Sekarang
        </a>
    </div>
</div>


    <!-- Sambutan + Statistik -->
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-6 mt-8">
        <!-- Sambutan Kepala Sekolah -->
        <div class="bg-white shadow rounded-xl p-6 flex items-start space-x-4">
            <img src="/image/fotoguru.png" class="w-20 h-20 rounded-full object-cover" alt="Kepala Sekolah">


            <div>
                <h2 class="font-bold text-lg">Sambutan Kepala Sekolah</h2>
                <p class="font-semibold text-blue-700">Anda Sri Dewi, S.E.</p>
                <p class="text-gray-600 text-sm mt-2">
                    Assalamualaikum warahmatullahi wabarakatuh. Segala puji bagi Allah Subhanahu wa ta’ala,
                    shalawat serta salam tercurah kepada Nabi Muhammad SAW beserta ...
                </p>
            </div>
        </div>

        <!-- Statistik Data -->
        <div class="bg-white shadow rounded-xl p-6 flex justify-around text-center">
            <div>
                <p class="text-3xl font-bold text-blue-600">7</p>
                <p class="text-gray-600 text-sm">Guru & Staf</p>
            </div>
            <div>
                <p class="text-3xl font-bold text-blue-600">90</p>
                <p class="text-gray-600 text-sm">Siswa</p>
            </div>
            
            <div>
                <p class="text-3xl font-bold text-blue-600">4</p>
                <p class="text-gray-600 text-sm">Rombel</p>
            </div>
        </div>
    </div>

    <!-- Agenda -->
    <div class="max-w-7xl mx-auto px-6 mt-10">
        <h2 class="text-xl font-bold text-gray-800 mb-4">Agenda</h2>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white p-4 shadow rounded-xl">
                <p class="font-semibold text-blue-700">Kelulusan & Kenaikan Kelas</p>
                <p class="text-sm text-gray-600">28 – 29 Juni 2025</p>
            </div>
            <div class="bg-white p-4 shadow rounded-xl">
                <p class="font-semibold text-blue-700">Pekan Ceria Semester 2</p>
                <p class="text-sm text-gray-600">9 – 13 Juni 2025</p>
            </div>
            <div class="bg-white p-4 shadow rounded-xl">
                <p class="font-semibold text-blue-700">Pekan Ceria Semester 2</p>
                <p class="text-sm text-gray-600">9 – 13 Juni 2025</p>
            </div>
        </div>
    </div>

    <!-- Berita, Artikel & Informasi -->
    <div class="max-w-7xl mx-auto px-6 mt-10">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold text-gray-800">Berita, Artikel & Informasi</h2>
            <a href="#" class="text-blue-600 hover:underline text-sm">Selengkapnya →</a>
        </div>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <img src="/image/berita.png" alt="Berita" class="w-full h-40 object-cover">
                <div class="p-4">
                    <p class="text-xs text-gray-500">23 Juni 2025</p>
                    <p class="font-semibold text-gray-800">Perpisahan RA Fadilah T.A 2024/2025 Angkatan ke 17</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <img src="/image/berita.png" alt="Berita" class="w-full h-40 object-cover">
                <div class="p-4">
                    <p class="text-xs text-gray-500">23 Juni 2025</p>
                    <p class="font-semibold text-gray-800">Perpisahan RA Fadilah T.A 2024/2025 Angkatan ke 17</p>
                </div>
            </div>
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <img src="/image/berita.png" alt="Berita" class="w-full h-40 object-cover">
                <div class="p-4">
                    <p class="text-xs text-gray-500">23 Juni 2025</p>
                    <p class="font-semibold text-gray-800">Perpisahan RA Fadilah T.A 2024/2025 Angkatan ke 17</p>
                </div>
            </div>
        </div>

    <!-- Staff Pengajar -->
    <div class="max-w-7xl mx-auto px-6 mt-12">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold text-gray-800">Staff Pengajar</h2>
            <a href="#" class="text-blue-600 hover:underline text-sm">Selengkapnya →</a>
        </div>
        <p class="text-gray-600 mb-6">Staf tenaga pengajar pada sekolah kami</p>
        <div class="grid sm:grid-cols-2 md:grid-cols-4 gap-6">
            <!-- Card Staff -->
            <div class="bg-white shadow rounded-xl p-6 text-center">
                <img src="/image/fotoguru.png" alt="Guru" class="w-24 h-24 mx-auto rounded-full mb-4">
                <h3 class="font-semibold text-gray-800">Alikha Maulida</h3>
                <p class="text-gray-600 text-sm">Guru Inti</p>
            </div>
            <div class="bg-white shadow rounded-xl p-6 text-center">
                <img src="/image/fotoguru.png" alt="Guru" class="w-24 h-24 mx-auto rounded-full mb-4">
                <h3 class="font-semibold text-gray-800">Alikha Maulida</h3>
                <p class="text-gray-600 text-sm">Guru Inti</p>
            </div>
            <div class="bg-white shadow rounded-xl p-6 text-center">
                <img src="/image/fotoguru.png" alt="Guru" class="w-24 h-24 mx-auto rounded-full mb-4">
                <h3 class="font-semibold text-gray-800">Alikha Maulida</h3>
                <p class="text-gray-600 text-sm">Guru Pembimbing</p>
            </div>
            <div class="bg-white shadow rounded-xl p-6 text-center">
                <img src="/image/fotoguru.png" alt="Guru" class="w-24 h-24 mx-auto rounded-full mb-4">
                <h3 class="font-semibold text-gray-800">Alikha Maulida</h3>
                <p class="text-gray-600 text-sm">Guru Pembimbing</p>
            </div>
        </div>
    </div>

    <!-- Testimoni -->
    <div class="bg-blue-50 mt-16 py-12">
        <div class="max-w-7xl mx-auto px-6">
            <h2 class="text-2xl font-bold text-gray-800 text-center mb-2">Testimoni</h2>
            <p class="text-center text-gray-600 mb-8">
                Testimoni dari alumni, orang tua mengenai sekolah kami
            </p>

            <div class="grid md:grid-cols-3 gap-6">
                <!-- Card 1 -->
                <div class="bg-white rounded-xl shadow p-6 flex flex-col justify-between">
                    <p class="text-gray-600 italic mb-6">
                        “Alhamdulillah anak kami Anya tahun ini penyelesaian pendidikan di RA Fadilah.
                        Dengan kekeluargaan yang diciptakan membuat anak didik nyaman dan senang untuk
                        bermain ke sekolah.”
                    </p>
                    <div class="flex items-center space-x-4 mt-auto">
                        <img src="/image/fotoguru.png" alt="Foto" class="w-12 h-12 rounded-full">
                        <div>
                            <p class="font-semibold text-gray-800">Bunda Alleryk</p>
                            <p class="text-sm text-gray-500">Alumni Angkatan 2024</p>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="bg-blue-500 text-white rounded-xl shadow p-6 flex flex-col justify-between">
                    <p class="italic mb-6">
                        “MasyaAllah sekali bang azzik hafalan asma ul-husna sudah lumayan banyak,
                        terimakasih bunda, terus semangat bunda guru mendidik anak-anak agar menjadi
                        anak sholeh dan sholehah.”
                    </p>
                    <div class="flex items-center space-x-4 mt-auto">
                        <img src="/image/fotoguru.png" alt="Foto" class="w-12 h-12 rounded-full border-2 border-white">
                        <div>
                            <p class="font-semibold">Oberon Shaw, MCH</p>
                            <p class="text-sm opacity-90">Alumni Angkatan 2022</p>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="bg-blue-500 text-white rounded-xl shadow p-6 flex flex-col justify-between">
                    <p class="italic mb-6">
                        “MasyaAllah sekali bang azzik hafalan asma ul-husna sudah lumayan banyak,
                        terimakasih bunda, terus semangat bunda guru mendidik anak-anak agar menjadi
                        anak sholeh dan sholehah.”
                    </p>
                    <div class="flex items-center space-x-4 mt-auto">
                        <img src="/image/fotoguru.png" alt="Foto" class="w-12 h-12 rounded-full border-2 border-white">
                        <div>
                            <p class="font-semibold">Oberon Shaw, MCH</p>
                            <p class="text-sm opacity-90">Alumni Angkatan 2023</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Lokasi & Hubungi Kami -->
        <div class="mt-2">
        <!-- Map -->
        <div class="w-full h-80">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15958.687942617486!2d101.416!3d0.507!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31d5ae3f1b9988a3%3A0x7a9d9f34d9c2f8b9!2sRaudhatul%20Athfal%20Fadhilah!5e0!3m2!1sid!2sid!4v1695712345678"
                width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </div>

        <!-- Contact Info -->
<div class="bg-blue-100 py-12">
    <div class="max-w-6xl mx-auto px-6 grid md:grid-cols-2 gap-10">
        
        <!-- Logo & Identitas -->
        <div class="flex flex-col items-center md:items-center text-center md:text-center">
            <!-- Logo -->
            <img src="/image/logo_RA.png" alt="Logo" class="w-40 h-40 mb-4">
            
            <!-- Identitas Sekolah -->
            <h3 class="font-bold text-lg text-gray-800">RA FADHILAH</h3>
            <p class="font-bold text-gray-800">YAYASAN DAREL FADHILAH</p>
            
            <p class="mt-4 font-bold text-gray-800">Akreditasi A</p>
            <p class="text-gray-700">NPSN : 69731079</p>
        </div>

        <!-- Detail Kontak -->
        <div class="text-gray-700 space-y-4">
            <h4 class="font-bold text-gray-600 uppercase">Hubungi Kami</h4>
            
            <p><span class="font-bold">Alamat Kantor :</span><br>
                CCV9+42C, Jl. Muhajirin, Sidomulyo Bar., Kec. Tampan, Kota Pekanbaru, Riau 28294
            </p>
            
            <p><span class="font-bold">E-Mail :</span><br>
                <a href="mailto:admin@rafadhilah.sch.id" class="text-blue-600 hover:underline">
                    admin@rafadhilah.sch.id
                </a>
            </p>
            
            <p><span class="font-bold">No Telepon :</span><br>0821 6207 736</p>
            
            <p><span class="font-bold">Whatsapp :</span><br>0821 6207 736<br>0822 8681 7315</p>
            
            <p><span class="font-bold">Jam Kerja :</span><br>Senin - Sabtu, 08.00 - 13.00 WIB</p>
        </div>
    </div>
</div>


        <!-- Footer -->
        <footer class="bg-blue-900 text-white py-4 text-center text-sm">
            <p>Copyright © 2025 Raudhatul Athfal Fadhilah Pekanbaru. All right reserved</p>
        </footer>
    </div>


    </div>
</div>
@endsection
