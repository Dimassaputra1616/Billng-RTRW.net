# RT RW NET PRO - Agent Instructions

Dokumen ini berisi panduan dan instruksi untuk AI Agent dalam mengembangkan project Billing RT RW Net.

## Project Stack

- **Framework**: Laravel 13
- **Admin Panel**: Filament v3 (Midnight Purple Cyberpunk Theme)
- **Database**: SQLite (`database/database.sqlite`)
- **Theme**: Deep Purple glassmorphism + aurora gradient background, forced dark mode
- **Font**: Inter (via Filament panel config)

## Core Logic & Rules

1. **Billing**: Invoice dibuat otomatis via command `app:generate-monthly-invoices`. Jatuh tempo default adalah tanggal 10 tiap bulan.
2. **Customers**: Pelanggan harus memiliki `internet_package_id`. Status default adalah `active`. Status options: active, inactive, isolated.
3. **Payments**: Saat payment dibuat, PaymentObserver otomatis mengubah status invoice menjadi `paid`.
4. **Aesthetics**: Gunakan Heroicons, warna Purple untuk primary, font Inter. Pastikan UI terasa premium dan modern dengan glassmorphism.
5. **Navigation**: Resources dikelompokkan dalam navigation groups: Manajemen Pelanggan, Keuangan, Pengaturan.
6. **Labels**: Semua label UI harus dalam Bahasa Indonesia.

## Database Schema

- **internet_packages**: name, speed_limit, price, description
- **customers**: name, phone_number, address, location_lat/lng, pppoe_username/password, static_ip, internet_package_id, status, installation_date
- **invoices**: customer_id, invoice_number, period_month, period_year, amount, status, due_date
- **payments**: invoice_id, payment_method, amount_paid, payment_date, attachment_path
- **settings**: key, value, group

## Login Credentials

- **Email**: admin@rtrw.net
- **Password**: password

## Roadmap & Progress

- [x] Initial setup Laravel 13 + Filament v3.
- [x] Database schema (Packages, Customers, Invoices, Payments).
- [x] Midnight Purple Cyberpunk theme with glassmorphism.
- [x] Stats Widgets di Dashboard (dynamic, real data).
- [x] Revenue Chart Widget (real data 6 bulan terakhir).
- [x] Navigation Groups (Manajemen Pelanggan, Keuangan, Pengaturan).
- [x] Navigation Badges (dynamic counts).
- [x] WhatsApp direct integration di tabel Customer.
- [x] Automated Monthly Billing Command.
- [x] Mikrotik API Integration (PPPoE/Status Sync).
- [x] Payment Confirmation & Receipt (PDF) Generation.
- [x] Indonesian labels on all forms and tables.
- [x] Database seeder with 12 customers, 5 packages, demo invoices & payments.
- [ ] WhatsApp Automated Notification (via Gateway API).
- [ ] Client Portal (Simple login for residents to see bills).
- [ ] Financial Reports (Monthly Profit/Loss).

## Notes for Agent

- Selalu prioritaskan keamanan data (validasi input).
- Pastikan semua Resource Filament memiliki icon yang deskriptif.
- Gunakan bahasa yang santai tapi profesional saat berinteraksi dengan user.
- Navigation group type harus `string | UnitEnum | null` (bukan `?string`).
- Semua model harus punya proper `$casts` dan `$fillable`.

## Tambahan untuk project ini

1. Tolong perbaiki logika query pada getTableQuery() di file Filament Widget untuk tabel "Pelanggan Jatuh Tempo". Saat ini datanya tidak akurat.

2. Ubah query-nya menggunakan Eloquent dan Carbon agar HANYA menampilkan pelanggan dengan status 'belum_bayar' DAN kolom 'due_date' berada tepat dalam rentang hari ini (now) sampai 3 hari ke depan. Pastikan data yang sudah lewat jatuh tempo (overdue) tidak ikut masuk ke tabel ini.

# bug relasi

- Ada masalah relasi data di Filament Widget untuk tabel "Pembayaran Terakhir". Kolom nama pelanggan saat ini kosong/blank.

- Tolong perbaiki dengan 2 langkah:

1. Pastikan model Payment sudah memiliki method relasi belongsTo() yang benar ke model Customer.
2. Update konfigurasi TextColumn di file Widget tabel tersebut agar memanggil relasi dengan dot notation, misalnya TextColumn::make('customer.name'). Tambahkan juga fitur searchable() dan sortable() di kolom tersebut.

## fitur broadcast WA

- Tolong buatkan fitur "Broadcast WA Semua" pada header actions di tabel pelanggan Filament. Jangan eksekusi prosesnya secara synchronous agar server tidak timeout.

- Langkah-langkahnya:

1. Buat satu Laravel Job baru bernama SendWhatsAppBroadcastJob yang menerima parameter data Customer.
2. Buat Action button di Filament header. Saat tombol diklik, ambil semua data pelanggan yang menunggak (jatuh tempo <= hari ini dan status 'belum_bayar').
3. Lakukan looping pada data pelanggan tersebut dan dispatch SendWhatsAppBroadcastJob ke background queue.
4. Tampilkan Filament Notification 'success' yang memberi tahu admin bahwa "Broadcast WA sedang diproses di latar belakang" setelah job di-dispatch.

## data grafik pendapatan

- Widget Chart "Grafik Pendapatan" di Filament saat ini masih kosong. Tolong isi dengan data dinamis.

- Gunakan package Flowframe/laravel-trend. Buat query untuk mengambil agregasi total (sum) dari kolom 'amount' (atau kolom nominal pembayaran) pada model Payment selama 6 bulan terakhir dengan interval per bulan (perMonth).

- Map hasilnya ke dalam array format 'datasets' dan 'labels' yang sesuai dengan struktur getFilters() dan getData() bawaan Filament Chart Widget. Gunakan warna hex '#3b82f6' untuk border dan background chart-nya.

## Membuat Tabel Tunggakan (Overdue)

- Logika H-3 sudah benar. Sekarang, tolong buatkan satu Filament Widget Table baru khusus untuk "Pelanggan Menunggak" (Overdue) dan letakkan di bawah tabel H-3.

## Debugging Kolom Pelanggan yang Kosong

- Konfigurasi TextColumn untuk relasi pelanggan di tabel "Pembayaran Terakhir" sudah jalan (search & sort muncul), tapi datanya masih blank/kosong.

Tolong analisa dan perbaiki berdasarkan kemungkinan berikut:

1. Cek skema database PostgreSQL. Pastikan kolom nama di tabel customers benar-benar bernama 'name'. Jika namanya 'nama_pelanggan' atau yang lain, tolong sesuaikan di TextColumn::make().
2. Pastikan foreign key di tabel payments benar (misalnya 'customer_id') dan datanya tidak NULL di database.
3. Cek kembali model Payment. Jika nama method relasinya bukan 'customer' (misal 'user' atau 'pelanggan'), sesuaikan pemanggilan dot notation-nya.

- Tolong perbaiki kodenya agar nama pelanggan benar-benar tampil.

## Format Nomor WA & Ganti Kolom Kecepatan

- Tolong optimalkan file Filament Resource untuk tabel Pelanggan dengan langkah berikut:

1. Pada TextColumn 'no_telepon', tambahkan method formatStateUsing() untuk memformat nomor yang berawalan '08' menjadi '628' secara otomatis khusus pada tampilannya saja. Tambahkan juga fitur copyable() agar admin mudah menyalin nomornya.
2. Hapus atau sembunyikan (toggleable(isToggledHiddenByDefault: true)) TextColumn 'kecepatan', karena informasinya sudah redundan dengan nama paket.
3. Sebagai gantinya, tambahkan TextColumn baru untuk 'alamat'. Gunakan method limit(30) agar teksnya tidak terlalu panjang dan merusak layout tabel.

## Setup Filter Status & Paket

- Tolong tambahkan fitur Filters pada getTableFilters() di file Filament Resource tabel Pelanggan.
- Buat dua SelectFilter:
    - Filter berdasarkan 'status' (Aktif, Terisolir, Non-Aktif).
    - Filter berdasarkan relasi 'paket' (ambil dari nama_paket di tabel internet_packages).
- Pastikan kedua filter ini berfungsi dengan baik untuk menyaring data di tabel.

## Validasi Form (Nomor HP & IP)

- Tolong perbarui Form Builder di Filament Resource untuk Pelanggan pada field berikut:

1. Untuk TextInput 'no_telepon': tambahkan prefix('+62'), hilangkan '0' di awal placeholder, dan tambahkan validasi numeric() serta unique(ignoreRecord: true). Pastikan saat save ke database, angka '62' digabung dengan inputan user.
2. Untuk TextInput 'static_ip': tambahkan validasi ipv4() agar user hanya bisa memasukkan format IP address yang benar.

## Tambahan Field Siklus Tagihan

- Pada bagian Section 'Pengaturan Internet' di Form Builder Pelanggan, tolong tambahkan satu field baru di sebelah 'Tanggal Pasang'.
- Buat field TextInput bernama 'tanggal_jatuh_tempo' (atau field yang sesuai di tabel customers untuk menandakan tanggal tagihan setiap bulannya).
- Berikan atribut numeric(), minValue(1), maxValue(28), dan label 'Tanggal Jatuh Tempo (Tgl 1-28)'. Berikan helperText('Tanggal tagihan setiap bulannya').

## Gabung Kolom Periode & Tambah View Action

- Tolong optimalkan file Filament Resource untuk tabel Tagihan/Invoice:

1. Hapus TextColumn 'bulan' dan 'tahun'. Ganti dengan satu TextColumn baru bernama 'periode' yang menggabungkan nilainya (contoh output: "Mei 2026").
2. Pada bagian getTableActions(), ubah agar tidak hanya menampilkan EditAction. Tolong tambahkan ViewAction() agar admin bisa melihat detail tagihan secara read-only.

## Action "Konfirmasi Pembayaran"

- Tolong tambahkan Action kustom pada baris tabel Tagihan (di getTableActions()) khusus untuk proses pembayaran dengan kriteria berikut:

1. Buat Action::make('bayar') dengan label 'Bayar', icon 'heroicon-o-banknotes', dan color 'success'.
2. Action ini HANYA BISA MUNCUL (visible) jika record tagihan tersebut statusnya 'Belum Bayar'.
3. Saat action ini diklik, tampilkan form modal (menggunakan form() bawaan Action) yang meminta inputan 'metode_pembayaran' (Select: Cash, Transfer, QRIS) dan 'tanggal_bayar' (DatePicker, default hari ini).
4. Setelah disubmit, update status tagihan menjadi 'Lunas' dan simpan data pembayarannya.

## Membuat Console Command (Mesin Pembuat Tagihan)

- Tolong buatkan sebuah Laravel Console Command baru bernama 'app:generate-monthly-invoices'.

- Logika di dalam handle() command tersebut harus seperti ini:

1. Ambil semua data Pelanggan (Customer) yang statusnya 'Aktif'.
2. Lakukan looping pada setiap pelanggan.
3. Di dalam loop, cek apakah 'tanggal_jatuh_tempo' pelanggan tersebut sama dengan hari ini (Carbon::now()->day).
4. Jika cocok, cek dulu ke tabel Invoices: Apakah pelanggan tersebut SUDAH memiliki tagihan untuk bulan dan tahun yang sedang berjalan? (Ini penting agar tidak terjadi double invoice).
5. Jika belum ada tagihan untuk bulan ini, buatkan record baru di tabel Invoices dengan data:
    - no_invoice: Generate otomatis (contoh: INV/Tahun/Bulan/IDPelanggan/Random).
    - customer_id: ID pelanggan tersebut.
    - amount: Ambil dari harga paket internet yang sedang digunakan pelanggan tersebut.
    - status: 'Belum Bayar'.
    - due_date: Tanggal jatuh tempo di bulan ini.
6. Berikan output di console 'Success generate invoice for customer: [Nama Pelanggan]' untuk setiap proses yang berhasil.

## Mendaftarkan ke Schedule (Menjalankan Mesin Otomatis)

- Setelah command 'app:generate-monthly-invoices' selesai dibuat, tolong daftarkan command tersebut ke dalam Laravel Task Scheduler (biasanya di routes/console.php atau app/Console/Kernel.php tergantung versi Laravel yang digunakan).
- Atur agar command tersebut berjalan secara otomatis setiap hari pada pukul 00:01 tengah malam (dailyAt('00:01')). Dengan begitu, sistem akan mengecek siapa saja yang jatuh tempo setiap harinya secara otomatis.

## Tips Tambahan

- Penting! Pastiin di tabel customers, lu punya relasi ke tabel internet_packages biar sistem bisa narik harga paketnya otomatis pas bikin tagihan.

- Tolong implementasikan Opsi 2 dan Opsi 3 sekaligus ke dalam command.
- Ubah signature command menjadi: 'app:generate-monthly-invoices {--all} {--force}'

- Logikanya:
    1. Jika flag '--all' ditambahkan saat command dijalankan di terminal, abaikan filter `where('billing_day', $today)`. Ambil semua customer yang berstatus aktif.
    2. Jika flag '--force' ditambahkan, abaikan pengecekan variabel $exists. Jika invoice untuk bulan dan tahun ini sudah ada, timpa (update) record tersebut, ATAU hapus record yang lama lalu create ulang.
    3. Jika dijalankan tanpa flag apapun, biarkan berjalan normal seperti biasa (hanya mengecek yang jatuh tempo hari ini dan belum ada invoice).

    ## style struk kwitansi invoice
    - Tolong rombak total dan perbagus desain file Blade template HTML yang digunakan untuk mencetak PDF Kwitansi Pembayaran. Desainnya saat ini terlalu basic.

    - Gunakan styling CSS internal dengan rapi (karena DomPDF/Snappy tidak bisa membaca external tailwind/css dengan baik). Buat struktur layout seperti ini:
    1. Fix Bug Icon: Hapus icon yang menyebabkan error '?' pada header. Ganti dengan teks biasa yang tebal, atau sediakan tag <img> kosong agar saya bisa menaruh logo perusahaan nanti.
    2. Header 2 Kolom: Buat bagian atas menjadi dua sisi menggunakan tabel/float. Sisi Kiri: Nama RT RW Net, Alamat Perusahaan, Kontak. Sisi Kanan: Tulisan "KWITANSI", No. Invoice, dan Tanggal Bayar.
    3. Info Pelanggan: Buat kotak/section rapi untuk menampilkan 'Tagihan Kepada: [Nama Pelanggan]', lengkap dengan Metodenya (QRIS/Transfer).
    4. Tabel Rincian: Buat sebuah tabel HTML <table> yang proper dengan border bawah pada header-nya. Kolomnya berisi: Deskripsi (contoh: Pembayaran Paket Silver 10 Mbps periode Mei 2026) dan Total Harga.
    5. Total & Watermark: Berikan styling tebal pada baris 'TOTAL BAYAR'. Tambahkan elemen stempel/tulisan "LUNAS" berwarna hijau dengan opacity rendah (transparan) yang ditempatkan secara absolut/miring di tengah kwitansi.

    ## Setup Folder & Install Dependencies
    - Tolong buatkan server WhatsApp Gateway menggunakan Node.js.
    - Langkah-langkahnya:
        1. Buat folder baru bernama 'wa-gateway' (sejajar dengan folder project Laravel, JANGAN di dalam folder Laravel).
        2. Di dalam folder tersebut, inisialisasi project Node.js (npm init -y).
        3. Install package yang dibutuhkan: express, whatsapp-web.js, qrcode-terminal, dan cors.

    ## Pembuatan Script Server WA (server.js)

    \_ Di dalam folder 'wa-gateway', buatkan file 'server.js'. Tulis kode Express.js dan whatsapp-web.js dengan ketentuan berikut:

1. Inisialisasi Express app berjalan di port 3000. Gunakan middleware express.json() dan cors().
2. Inisialisasi WA Client menggunakan LocalAuth (agar sesi login tersimpan dan tidak perlu scan QR terus-menerus saat server direstart).
3. Saat event 'qr' muncul, generate QR code ke terminal menggunakan qrcode-terminal.
4. Saat event 'ready', berikan console.log("WhatsApp Client is ready!").
5. Buat satu endpoint POST '/send-message'. Endpoint ini menerima JSON body: { "number": "628xxx", "message": "Teks pesan" }.
6. Di dalam endpoint tersebut, format nomor agar berakhiran '@c.us' (standar whatsapp-web.js) dan kirim pesan menggunakan client.sendMessage(). Kembalikan response JSON sukses/error.

## Setup Folder & Install Dependencies (Node.js)

- Tolong buatkan folder 'wa-gateway' di DALAM folder workspace saat ini (Billing/wa-gateway).
- Setelah itu, langsung eksekusi pembuatan server Node.js-nya:

1. Inisialisasi npm (npm init -y) di dalam folder wa-gateway.
2. Install dependencies: express, whatsapp-web.js, qrcode-terminal, dan cors.
3. Buat file server.js. Setup Express (port 3000), gunakan LocalAuth untuk WA client, generate QR dengan qrcode-terminal saat event 'qr', dan berikan log "WhatsApp Client is ready!" saat 'ready'.
4. Buat endpoint POST '/send-message' yang menerima JSON { "number": "628xxx", "message": "Teks pesan" }. Format nomor dengan akhiran '@c.us' dan kirim menggunakan client.sendMessage().
5. PENTING: Tambahkan '/wa-gateway' ke dalam file .gitignore milik root project Laravel agar folder node_modules ini tidak ikut terlacak oleh git.

- Tolong update inisialisasi Client whatsapp-web.js di dalam file server.js.
- Tambahkan opsi puppeteer arguments '--no-sandbox' dan '--disable-setuid-sandbox' agar aman dijalankan di environment Linux.

- Contohnya menjadi seperti ini:
- const client = new Client({
  authStrategy: new LocalAuth(),
  puppeteer: {
  args: ['--no-sandbox', '--disable-setuid-sandbox']
  }
  });

## integrasi whatsapp

- Tolong buatkan integrasi HTTP Client di Laravel untuk menyambungkan sistem dengan Node.js WhatsApp Gateway.
  Lakukan langkah-langkah berikut:

1. Buat sebuah service class baru di 'app/Services/WhatsAppService.php'.
2. Di dalam class tersebut, buat method static 'sendMessage($number, $message)'. Method ini bertugas melakukan HTTP POST request menggunakan Facade Http Laravel (Http::post) ke 'http://localhost:3000/send-message' dengan membawa payload JSON { "number": $number, "message": $message }.
3. Buka kembali console command 'GenerateMonthlyInvoices' yang sebelumnya sudah kita buat.
4. Di dalam loop, TEPAT SETELAH kode pembuatan (create) tagihan baru berhasil dieksekusi, panggil WhatsAppService::sendMessage() untuk mengirimkan notifikasi ke nomor telepon pelanggan tersebut.
5. Gunakan format pesan (gunakan PHP heredoc atau string interpolation):

- "Halo _[Nama Pelanggan]_, tagihan internet RT RW NET PRO untuk periode bulan ini telah terbit.
- No. Tagihan: _[Nomor Invoice]_
- Total Tagihan: _Rp [Jumlah (format number_format)]_
- Jatuh Tempo: _[Tanggal Jatuh Tempo]_

- Mohon segera melakukan pembayaran. Abaikan pesan ini jika sudah membayar. Terima kasih."

## Sinkronisasi Waktu (Timezone)

- Tolong ubah pengaturan timezone di file 'config/app.php' menjadi 'Asia/Jakarta'.
- Ini penting agar jadwal jam 8 pagi yang kita buat sesuai dengan waktu Indonesia Barat (WIB).

## Update Jadwal Eksekusi (Schedule)

- Tolong update jadwal eksekusi untuk command 'app:generate-monthly-invoices' di file 'routes/console.php' (atau app/Console/Kernel.php).
- Ubah yang tadinya ->dailyAt('00:01') menjadi ->dailyAt('08:00').
- Dengan begini, sistem akan otomatis membuat tagihan dan mengirimkan pesan WhatsApp tepat pada jam 8 pagi setiap harinya.

## Install Package & Plugin

- Tolong pasang fitur Activity Log di Filament menggunakan package Spatie.
- Lakukan langkah berikut:

1. Install package utama: composer require spatie/laravel-activitylog
2. Install Filament plugin: composer require z3d0x/filament-logger
3. Publish dan jalankan migration spatie:
   php artisan vendor:publish --provider="Spatie\Activitylog\ActivitylogServiceProvider" --tag="activitylog-migrations"
   php artisan migrate
4. Publish config plugin-nya: php artisan vendor:publish --tag=filament-logger-config

## Pendaftaran Plugin di Filament Panel

- Setelah terinstall, tolong daftarkan plugin FilamentLoggerPlugin ke dalam file app/Providers/Filament/AdminPanelProvider.php.
- Tambahkan di bagian ->plugins([ ... ]) seperti ini:
  ->plugins([
  \Z3d0x\FilamentLogger\FilamentLoggerPlugin::make(),
  ])

## Pelacakan di Model (Penting!)

- Sekarang, tolong tambahkan pelacakan aktivitas pada 3 Model utama kita: Customer, Invoice, dan Payment.
  Di setiap model tersebut:

1. Use trait: Spatie\Activitylog\Traits\LogsActivity;
2. Tambahkan fungsi getActivitylogOptions():
   public function getActivitylogOptions(): LogOptions
   {
   return LogOptions::defaults()
   ->logUnguarded()
   ->logOnlyDirty()
   ->dontSubmitEmptyLogs();
   }

- Dengan begini, setiap aksi Create, Update, dan Delete pada Pelanggan, Tagihan, dan Pembayaran akan tercatat otomatis.

## Kita akan membangun fitur Integrasi MikroTik. Tolong siapkan fondasinya dengan langkah-langkah berikut:

1. Buat migration baru untuk menambahkan kolom 'pppoe_username' (string, nullable) pada tabel 'customers'. Jalankan php artisan migrate.
2. Update model Customer dengan menambahkan 'pppoe_username' ke dalam $fillable.
3. Update resource Filament CustomerResource agar form input 'pppoe_username' muncul saat admin menambah/mengedit pelanggan.
4. Install package untuk koneksi MikroTik: composer require evilbox/mikrotik-routeros-api-php
5. Tambahkan variabel environment berikut ke dalam file .env:
   MIKROTIK_HOST=192.168.1.1
   MIKROTIK_USER=admin
   MIKROTIK_PASS=
   MIKROTIK_PORT=8728
6. Buat class baru di 'app/Services/MikrotikService.php'.
7. Di dalam MikrotikService, buat method connect() yang membaca kredensial dari .env dan menginisialisasi RouterosAPI.
8. Buat method isolateCustomer($pppoe_username) di dalam MikrotikService yang bertugas mencari user di '/ppp/secret/' berdasarkan nama, lalu melakukan perintah 'disable=yes'.

## Fondasi MikroTik sudah mantap! Sekarang tolong buatkan sistem "Algojo" otomatis dengan langkah berikut:

1. Buat console command baru: 'app:isolate-overdue-customers'.
2. Logika di dalam handle():
    - Cari semua Customer yang statusnya 'aktif' (atau 'Aktif').
    - Cek apakah mereka punya Invoice yang 'unpaid' dan 'due_date' nya sudah lebih kecil dari hari ini (past(now())).
    - Untuk setiap customer yang bandel ini:
      a. Panggil MikrotikService::isolateCustomer($customer->pppoe_username).
      b. Ubah status customer tersebut di database menjadi 'terisolir' atau 'non-aktif' (sesuaikan dengan enum status yang kita punya).
      c. Kirim WhatsApp via WhatsAppService: "Mohon maaf, koneksi internet Anda sementara kami isolir karena tagihan [No Invoice] sebesar [Nominal] telah melewati jatuh tempo. Segera lakukan pembayaran untuk mengaktifkan kembali."
      d. Berikan log info di terminal: "Customer [Nama] berhasil diisolasi."

3. Daftarkan command ini di 'bootstrap/app.php' (atau Kernel) agar jalan otomatis setiap hari jam 09:00 pagi (satu jam setelah tagihan dikirim, atau bisa di jam yang sama).

4. Tambahkan logic di Payment Observer atau Controller:
    - Jika admin menginput pembayaran dan tagihan tersebut menjadi 'Lunas', cek apakah customer tersebut berstatus 'terisolir'.
    - Jika iya, otomatis panggil MikrotikService::activateCustomer($customer->pppoe_username) dan kembalikan status customer menjadi 'aktif'.
