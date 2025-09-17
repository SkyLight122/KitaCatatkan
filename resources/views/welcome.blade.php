<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    @vite('resources/css/app.css')
</head>

</html>

<body>
    <div class="flex justify-between fixed top-0 left-0 w-full bg-white z-50 p-8 border-b">
        <img src="{{asset('images/Logo(Hitam).png')}}"
            class="ml-[70px] h-auto object-contain w-[90px] md:w-[120px] lg:w-[150px] transition-all duration-300 ease-in-out"
            alt="logo(hitam)">
        <div>
            <flux:button href="{{ route('login') }}" class="pl-[30px] pr-[30px]" variant="primary">Login</flux:button>
            <flux:button href="{{ route('register') }}" class="pl-[30px] pr-[30px]" variant="primary">Register
            </flux:button>
        </div>
    </div>
    <div class="relative mt-[100px]">
        <img src="{{asset('images/BackgroundLandingPage.png')}}" class="scale-90 mt-[-70px]">
        <div
            class="absolute top-25 md:top-35 lg:top-50 left-90 md:left-120 lg:left-180 z-0 transition-all duration-300 ease-in-out">
            <img src="{{asset('images/logosolohitam.png')}}"
                class="w-[60px] md:w-[90px] lg:w-[120px] transition-all duration-300 ease-in-out">
            <img src="{{asset('images/KamiCatatkan.hitam.png')}}"
                class="mt-[15px] md:mt-[20px] lg:mt-[25px] ml-[-63px] md:ml-[-65px] lg:ml-[-70px] w-[240px] md:w-[270px] lg:w-[300px] transition-all duration-300 ease-in-out">
            <h4
                class="text-center w-[330px] md:w-[360px] lg:w-[420px] ml-[-130px] md:ml-[-140px] lg:ml-[-150px] mt-[10px] md:mt-[15px] lg:mt-[20px] text-xs md:text-sm lg:text-base transition-all duration-300 ease-in-out">
                "Seluruh komitmen Anda, kini terorganisir dalam satu tempat. Temui kalender yang dirancang dengan indah
                dan terintegrasi sepenuhnya untuk mendukung pekerjaan dan kehidupan Anda.</h4>
            <flux:button variant="primary"
                class="mt-[10px] md:mt-[15px] lg:mt-[20px] ml-[-30px] md:ml-[-25px] lg:ml-[-15px] px-[10px] md:px-[15px] lg:px-[20px] ">
                Dapatkan Segera</flux:button>
        </div>
        <div
            class="absolute top-100 md:top-130 lg:top-180 left-10 md:left-15 lg:left-27 transition-all duration-300 ease-in-out">
            <img src="{{asset('images/gambarutama.png')}}"
                class="object-fit w-[700px] md:w-[900px] lg:w-[1300px] h-[250px] md:h-[375px] lg:h-[500px] rounded-[10px] shadow-md shadow-gray-800">
        </div>
        <div class="absolute bg-[#F6F6F6] w-full h-[1050px] md:h-[1050px] lg:h-[650px] top-190 md:top-265 lg:top-340">
            <div class="ml-[100px] mt-[40px]">
                <p class="mb-[-5px]">Waktu</p>
                <h1 class="text-2xl md:text-3xl lg:text-5xl font-semibold mt-2">Management waktu, kini lebih sederhana.
                </h1>
                <p class="w-[525px] md:w-[725px] lg:w-[925px] text-xs md:text-sm lg:text-base mt-[10px]">Aplikasi ini
                    dirancang khusus untuk pelajar dalam mengelola kegiatan belajar dan kerja kelompok secara efektif.
                    Dilengkapi dengan fitur catatan pintar, pembagian kelompok otomatis, ruang obrolan khusus, serta
                    pengingat deadline, setiap tugas dapat terselesaikan tepat waktu dengan lebih teratur.</p>
            </div>
            <div class="flex flex flex-col lg:flex-row">
                <div class="ml-[100px] md:ml-[175px] lg:ml-[100px]">
                    <div
                        class="w-[40rem] bg-white h-[25rem] mt-[1.5rem] p-[2rem] rounded-[10px] ml-[-50px] md:ml-[0px] lg:ml-[0px] scale-85 md:scale-100 lg:scale-100">
                        <div class="flex">
                            <img src="{{asset('images/lonceng.png')}}" class="w-[1.5rem] h-[1.5rem]">
                            <p class="text-[20px] font-semibold mt-2 mt-[-5px] ml-[10px]">Lihat jadwal dengan cepat</p>
                        </div>
                        <p>Bergabung ke rapat langsung dari panel menu sehingga Anda dapat fokus pada pekerjaan penting.
                        </p>
                        <img src="{{asset('images/lihatjadwaldengancepat.png')}}"
                            class="h-[16rem] w-full object-cover rounded-[10px] mt-[10px]">
                    </div>
                </div>
                <div class="mr-[100px] mt-[15px]">
                    <div>
                        <p
                            class="text-end w-[525px] md:w-[725px] lg:w-[625px] text-xs md:text-sm lg:text-base ml-[120px] md:ml-[70px] lg:ml-[90px]">
                            Dengan sistem prioritas, kalender akademik, dan ruang kolaborasi yang intuitif, aplikasi ini
                            menghadirkan pengalaman belajar yang terorganisir, efisien, dan sesuai dengan kebutuhan
                            sekolah modern.</p>
                    </div>
                    <div class="flex space-between ml-[50px] lg:ml-[0px] md:ml-[100px]">
                        <div class="w-[20rem] bg-white h-[19.5rem] mt-[1.5rem] p-[1rem] rounded-[10px] ml-[2.5rem]">
                            <p class="text-[20px] font-semibold mt-[13px] md:mt-[10px] lg:mt-[7px]">Bekerja dengan
                                responsif</p>
                            <p
                                class="w-full text-wrap text-xs md:text-sm lg:text-base mb-[20px] md:mb-[10px] lg:mb-[0px]">
                                Berkolaborasi dengan tim global secara mudah karena selalu langsung terupdate</p>
                            <img src="{{asset('images/bekerjadenganresponsif.png')}}"
                                class="rounded-[10px] mt-[7px] h-[10rem] object-fit">
                        </div>
                        <div class="w-[20rem] bg-white h-[19.5rem] mt-[1.5rem] p-[1rem] rounded-[10px] ml-[2.5rem]">
                            <img src="{{asset('images/calendar.png')}}"
                                class="rounded-[10px] mt-[13px] md:mt-[10px] lg:mt-[7px] mb-[20px] md:mb-[10px] lg:mb-[0px] h-[10rem] object-fit">
                            <p class="text-[20px] font-semibold mt-[5px]">Desain modern</p>
                            <p class="w-full text-wrap text-xs md:text-sm lg:text-base">Gunakan menu perintah dan
                                pintasan untuk mengefisienkan alur kerja.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="absolute top-470 md:top-540 lg:top-515 left-20 md:left-25 lg:left-27 flex flex-col lg:flex-row">
            <div>
                <div class="w-[37rem] md:w-[50rem] lg:w-[50rem] bg-[#F6F6F6] p-[2rem] rounded-[10px] h">
                    <p class="text-xl md:text-2xl lg:text-3xl font-semibold ">Notifikasi Pengingat</p>
                    <p class="w-[30rem] md:w-[30rem] lg:w-[40rem] text-xs md:text-sm lg:text-base">Notifikasi otomatis
                        saat mendekati jadwal pengumpulan tugas atau ujian.
                        Bebas mengatur waktunya mulai dari 3 hari sebelum sampai
                        beberapa jam sebelum deadline</p>
                </div>
                <div class="flex">
                    <img src="{{asset('images/gamabar2.png')}}"
                        class="object-fit rounded-[10px] w-[21rem] md:w-[28rem] lg:w-[28rem] h-[25.5rem] mt-[15px]">
                    <img src="{{asset('images/gambar3.png')}}"
                        class="object-fit rounded-[10px] w-[15rem] md:w-[21rem] lg:w-[21rem] h-[20rem] mt-[15px] ml-[15px]">
                </div>
            </div>
            <div class="ml-[15px]  md:mt-[30px] lg:mt-[0px]">
                <div>
                    <p class="text-4xl font-semibold">Tipe Notifikasi.</p>
                    <p>Bagian Notofikasi disesuaikan dengan aktivitas yang dilakukan oleh user</p>
                </div>
                <x-welcome-cards img="{{asset('images/arrowup.png')}}" judul="Berbasis Prioritas"
                    deskripsi="Prioritas dari aktivitas yang tergolong urgent, normal, low akan dibedakan berdasarkan warn"></x-welcome-cards>
                <x-welcome-cards img="{{asset('images/person.png')}}" judul="Notifikasi Kelompok"
                    deskripsi="Langsung setelah guru menentukan kelompok dengan notifikasi personal."></x-welcome-cards>
                <x-welcome-cards img="{{asset('images/toa.png')}}" judul="Smart Reminder"
                    deskripsi="Sistem pintar yang mendeteksi tugas yang belum selesai."></x-welcome-cards>
                <x-welcome-cards img="{{asset('images/setting.png')}}" judul="Pengaturan Notifikasi"
                    deskripsi="Menjadikan fitur notofikasi tidak kaku, sesuai dengan keinginan pengguna"></x-welcome-cards>
            </div>
        </div>
        <div
            class="absolute bg-[#F6F6F6] w-full h-[1100px] md:h-[1100px] lg:h-[650px] top-775 md:top-850 lg:top-675 p-[5rem] flex border-b-[2px]">
            <div>
                <div>
                    <p class="text-2xl md:text-3xl lg:text-5xl font-semibold">Kolaborasi.</p>
                    <p>Tingkatkan efisiensi tim Anda dengan sistem untuk kolaborasi.</p>
                </div>
                <div class="flex mt-[4.5rem] flex-col lg:flex-row">
                    <div>
                        <div>
                            <p class="text-xl md:text-2xl lg:text-3xl font-semibold">Ruang diskusi khusus.</p>
                            <p class="w-[25rem] mt-[1rem] text-lg">Guru dapat langsung membagikan daftar kelompok
                                melalui aplikasi. Setiap kelompok otomatis memiliki ruang kerja bersama.</p>
                        </div>
                        <div class="mt-[4.5rem]">
                            <p class="text-xl md:text-2xl lg:text-3xl font-semibold">Kalender Kolaboratif.</p>
                            <p class="w-[25rem] mt-[1rem] text-lg">Jadwal presentasi, ujian, dan deadline tugas otomatis
                                masuk ke kalender kelompok.
                                Semua anggota mendapat update yang sama tanpa harus menunggu pengumuman manual.</p>
                        </div>
                    </div>
                    <div class="ml-[2rem] mt-[4.5rem] lg:ml-[0rem] lg:mt-[0rem]">
                        <div class="ml-[-30px] lg:ml-[0px]">
                            <p class="text-3xl font-semibold ml-[-2px]">Catatan & Tugas.</p>
                            <p class="w-[25rem] mt-[1rem] text-lg">Siswa bisa menulis catatan bersama secara real-time.
                                Mendukung upload file dan komentar langsung di dokumen.</p>
                        </div>
                        <div class="mt-[4.5rem] ml-[-30px] lg:ml-[0px]">
                            <p class="text-3xl font-semibold">Kalender Kolaboratif.</p>
                            <p class="w-[25rem] mt-[1rem] text-lg">Guru bisa memberi komentar dan nilai langsung di
                                dalam tugas kelompok.
                                Siswa bisa melihat progres serta evaluasi secara transparan.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <img src="{{asset('images/kolaborasi.png')}}"
                    class="w-[20rem] h-[30rem] md:h-full lg:h-full md:ml-[10rem] lg:ml-[10rem] object-contain w-full h-full">
            </div>
        </div>
        <div
            class="p-[4rem] flex gap-10 lg:gap-50 flex-col grip-col-2 md:flex-row lg:flex-row mt-[160rem] md:mt-[140rem] lg:mt-[0rem]">
            <div>
                <img src="{{asset('images/logo(hitam).png')}}" width="200" class="object-cover">
                <div class="flex mt-[1rem] gap-5">
                    <img src="{{asset('images/Instagram.png')}}" width="25">
                    <img src="{{asset('images/Twitter.png')}}" width="25">
                    <img src="{{asset('images/Linkedin.png')}}" width="25">
                    <img src="{{asset('images/Facebook App Symbol.png')}}" width="25">
                    <img src="{{asset('images/Youtube.png')}}" width="25">
                </div>
                <div class="mt-[10px]">
                    <flux:link href="#" variant="ghost">Pengaturan Cookie</flux:link>
                </div>

                <p class="mt-[5px]">© 2025 KitaCatatkan. All Rights Reserved. <br> Dikembangkan untuk mendukung
                    pencatatan<br>jadwal di era digital.</p>
            </div>
            <div class="flex flex-col">
                <p class="font-bold">Perusahaan</p>
                <flux:link href="#" variant="ghost">Tentang Kami</flux:link>
                <flux:link href="#" variant="ghost">Karir</flux:link>
                <flux:link href="#" variant="ghost">Keamanan</flux:link>
                <flux:link href="#" variant="ghost">Status</flux:link>
                <flux:link href="#" variant="ghost">Ketentuan & Privasi</flux:link>
            </div>
            <div class="flex flex-col">
                <p class="font-bold">Sumber Daya</p>
                <flux:link href="#" variant="ghost">Pusat Bantuan</flux:link>
                <flux:link href="#" variant="ghost">Harga</flux:link>
                <flux:link href="#" variant="ghost">Blog</flux:link>
                <flux:link href="#" variant="ghost">Komunitas</flux:link>
                <flux:link href="#" variant="ghost">Integrasi</flux:link>
                <flux:link href="#" variant="ghost">Afiliasi</flux:link>
            </div>
            <div class="flex flex-col">
                <p class="font-bold">Sumber Daya</p>
                <flux:link href="#" variant="ghost">Perusahaan Kecil</flux:link>
                <flux:link href="#" variant="ghost">Perusahaan Besar</flux:link>
                <flux:link href="#" variant="ghost">Personal</flux:link>
            </div>
        </div>
    </div>
</body>