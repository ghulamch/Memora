<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kebijakan Privasi - Memora Photo Uploader</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.8;
            color: #333;
            background: #f5f5f5;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #1a1a2e;
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1em;
        }
        
        .last-updated {
            background: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            border-left: 4px solid #60a5fa;
        }
        
        h2 {
            color: #1a1a2e;
            margin-top: 40px;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #60a5fa;
            font-size: 1.8em;
        }
        
        h3 {
            color: #2d2d2d;
            margin-top: 25px;
            margin-bottom: 15px;
            font-size: 1.3em;
        }
        
        p {
            margin-bottom: 15px;
            text-align: justify;
        }
        
        ul {
            margin-left: 30px;
            margin-bottom: 20px;
        }
        
        li {
            margin-bottom: 10px;
        }
        
        .highlight {
            background: #fff3cd;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #fbbf24;
            margin: 20px 0;
        }
        
        .important {
            background: #f8d7da;
            padding: 20px;
            border-radius: 5px;
            border-left: 4px solid #ef4444;
            margin: 20px 0;
        }
        
        .contact-info {
            background: #d1f4e0;
            padding: 20px;
            border-radius: 5px;
            margin-top: 30px;
            border-left: 4px solid #4ade80;
        }
        
        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 0.9em;
        }
        
        strong {
            color: #1a1a2e;
        }
        
        a {
            color: #60a5fa;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
        
        code {
            background: #f5f5f5;
            padding: 2px 6px;
            border-radius: 3px;
            font-family: 'Courier New', monospace;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📸 Kebijakan Privasi</h1>
        <p class="subtitle">Memora Photo Uploader</p>
        
        <div class="last-updated">
            <strong>Terakhir diperbarui:</strong> 31 Desember 2024<br>
            <strong>Berlaku sejak:</strong> 31 Desember 2024
        </div>

        <p>Terima kasih telah menggunakan <strong>Memora Photo Uploader</strong> ("Aplikasi"). Kami berkomitmen untuk melindungi privasi Anda. Kebijakan Privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi Anda saat menggunakan Aplikasi kami.</p>

        <div class="highlight">
            <strong>📌 Ringkasan Singkat:</strong>
            <ul>
                <li>Kami <strong>TIDAK mengumpulkan</strong> data pribadi Anda</li>
                <li>Kami <strong>TIDAK menggunakan</strong> analytics atau tracking</li>
                <li>Kami <strong>TIDAK menampilkan</strong> iklan</li>
                <li>Foto yang Anda upload dikirim ke server yang <strong>ANDA tentukan</strong></li>
                <li>Semua data disimpan secara <strong>lokal</strong> di perangkat Anda</li>
            </ul>
        </div>

        <h2>1. Informasi yang Kami Kumpulkan</h2>
        
        <h3>1.1 Informasi yang Disimpan Lokal</h3>
        <p>Aplikasi menyimpan informasi berikut secara <strong>lokal di perangkat Anda</strong>:</p>
        <ul>
            <li><strong>API URL:</strong> Alamat server tujuan upload foto</li>
            <li><strong>Bearer Token:</strong> Token autentikasi untuk API (jika Anda masukkan)</li>
            <li><strong>Session Code:</strong> Kode sesi untuk identifikasi upload (jika Anda masukkan)</li>
            <li><strong>Statistik Upload:</strong> Jumlah foto yang diupload, antrian, dll.</li>
            <li><strong>Riwayat File:</strong> Daftar file yang sudah diproses</li>
        </ul>
        
        <p><strong>Catatan Penting:</strong> Data ini disimpan menggunakan <code>SharedPreferences</code> Android dan HANYA tersedia di perangkat Anda. Kami TIDAK memiliki akses ke data ini.</p>

        <h3>1.2 Akses ke Foto</h3>
        <p>Aplikasi memerlukan izin untuk:</p>
        <ul>
            <li><strong>READ_MEDIA_IMAGES</strong> (Android 13+) atau <strong>READ_EXTERNAL_STORAGE</strong> (Android 12 ke bawah)</li>
            <li>Izin ini digunakan HANYA untuk mendeteksi foto baru di galeri Anda</li>
            <li>Foto TIDAK disalin atau disimpan oleh aplikasi</li>
            <li>Foto langsung diupload ke server yang Anda tentukan</li>
        </ul>

        <h3>1.3 Informasi yang TIDAK Kami Kumpulkan</h3>
        <p>Kami <strong>TIDAK mengumpulkan</strong>:</p>
        <ul>
            <li>Nama, email, atau informasi pribadi lainnya</li>
            <li>Lokasi geografis</li>
            <li>Kontak atau daftar teman</li>
            <li>Data penggunaan atau analytics</li>
            <li>IMEI, nomor telepon, atau identifier perangkat lainnya</li>
            <li>Cookies atau tracking data</li>
        </ul>

        <h2>2. Bagaimana Kami Menggunakan Informasi</h2>
        
        <h3>2.1 Penggunaan Data Lokal</h3>
        <p>Informasi yang disimpan secara lokal digunakan untuk:</p>
        <ul>
            <li><strong>Konfigurasi Aplikasi:</strong> Mengingat pengaturan API Anda</li>
            <li><strong>Tracking Upload:</strong> Menghindari upload duplikat</li>
            <li><strong>Statistik:</strong> Menampilkan jumlah foto yang berhasil diupload</li>
        </ul>

        <h3>2.2 Upload Foto</h3>
        <p>Saat Anda mengaktifkan monitoring:</p>
        <ul>
            <li>Aplikasi mendeteksi foto baru di galeri Anda</li>
            <li>Foto diupload ke <strong>server yang ANDA tentukan</strong> menggunakan API URL yang Anda masukkan</li>
            <li>Aplikasi TIDAK menyimpan salinan foto</li>
            <li>Aplikasi TIDAK mengirim foto ke server kami (karena kami tidak memiliki server)</li>
        </ul>

        <div class="important">
            <strong>⚠️ Penting:</strong> Kami TIDAK memiliki kontrol atas server tujuan upload Anda. Kebijakan privasi server tersebut mungkin berbeda. Pastikan Anda memercayai server yang Anda gunakan.
        </div>

        <h2>3. Berbagi Informasi</h2>
        
        <p>Kami <strong>TIDAK membagikan</strong> informasi Anda kepada pihak ketiga karena:</p>
        <ul>
            <li>Kami tidak mengumpulkan data pribadi Anda</li>
            <li>Semua data disimpan secara lokal di perangkat Anda</li>
            <li>Aplikasi tidak menggunakan layanan analytics third-party</li>
            <li>Aplikasi tidak menggunakan advertising networks</li>
        </ul>

        <h2>4. Keamanan Data</h2>
        
        <h3>4.1 Penyimpanan Lokal</h3>
        <p>Data yang disimpan di perangkat Anda dilindungi oleh:</p>
        <ul>
            <li>Android app sandbox (isolasi aplikasi)</li>
            <li>Enkripsi perangkat (jika Anda aktifkan di pengaturan Android)</li>
            <li>Akses terbatas hanya untuk aplikasi ini</li>
        </ul>

        <h3>4.2 Transmisi Data</h3>
        <p>Saat upload foto:</p>
        <ul>
            <li>Koneksi menggunakan protokol yang Anda tentukan (HTTP/HTTPS)</li>
            <li>Kami sangat menyarankan menggunakan <strong>HTTPS</strong> untuk keamanan</li>
            <li>Bearer Token dikirim melalui header HTTP Authorization</li>
        </ul>

        <div class="highlight">
            <strong>💡 Rekomendasi:</strong> Selalu gunakan HTTPS untuk API URL Anda agar data terenkripsi selama transmisi.
        </div>

        <h2>5. Izin Aplikasi</h2>
        
        <p>Aplikasi memerlukan izin berikut:</p>
        
        <h3>5.1 Izin Wajib</h3>
        <ul>
            <li><strong>INTERNET:</strong> Untuk upload foto ke server Anda</li>
            <li><strong>READ_MEDIA_IMAGES / READ_EXTERNAL_STORAGE:</strong> Untuk mendeteksi foto baru</li>
            <li><strong>FOREGROUND_SERVICE:</strong> Untuk monitoring di background</li>
            <li><strong>POST_NOTIFICATIONS:</strong> Untuk menampilkan notifikasi status</li>
        </ul>

        <h3>5.2 Izin Opsional</h3>
        <ul>
            <li><strong>REQUEST_IGNORE_BATTERY_OPTIMIZATIONS:</strong> Agar aplikasi tidak dihentikan saat idle (Anda bisa menolak)</li>
            <li><strong>RECEIVE_BOOT_COMPLETED:</strong> Untuk auto-start setelah reboot (jika Anda aktifkan)</li>
        </ul>

        <h2>6. Data Anak-Anak</h2>
        
        <p>Aplikasi ini <strong>TIDAK ditujukan</strong> untuk anak-anak di bawah usia 13 tahun. Kami tidak secara sengaja mengumpulkan informasi pribadi dari anak-anak.</p>
        
        <p>Jika Anda adalah orang tua atau wali dan mengetahui bahwa anak Anda telah memberikan informasi pribadi kepada kami, silakan hubungi kami agar kami dapat mengambil tindakan yang diperlukan.</p>

        <h2>7. Hak Pengguna</h2>
        
        <p>Anda memiliki hak untuk:</p>
        <ul>
            <li><strong>Mengakses Data:</strong> Semua data tersimpan di perangkat Anda (Settings > Apps > Memora > Storage)</li>
            <li><strong>Menghapus Data:</strong> Gunakan tombol "Reset" di aplikasi atau uninstall aplikasi</li>
            <li><strong>Mencabut Izin:</strong> Settings > Apps > Memora > Permissions</li>
            <li><strong>Menghentikan Monitoring:</strong> Klik tombol "Stop" di aplikasi</li>
        </ul>

        <h2>8. Penyimpanan dan Penghapusan Data</h2>
        
        <h3>8.1 Durasi Penyimpanan</h3>
        <p>Data disimpan di perangkat Anda hingga:</p>
        <ul>
            <li>Anda menghapus data melalui tombol "Reset"</li>
            <li>Anda menguninstall aplikasi</li>
            <li>Anda menghapus data aplikasi melalui pengaturan Android</li>
        </ul>

        <h3>8.2 Cara Menghapus Data</h3>
        <p>Untuk menghapus semua data aplikasi:</p>
        <ol>
            <li>Buka aplikasi Memora Photo Uploader</li>
            <li>Klik tombol "Reset"</li>
            <li>Atau: Settings > Apps > Memora Photo Uploader > Storage > Clear Data</li>
        </ol>

        <h2>9. Layanan Pihak Ketiga</h2>
        
        <p>Aplikasi ini <strong>TIDAK menggunakan</strong> layanan pihak ketiga seperti:</p>
        <ul>
            <li>Google Analytics</li>
            <li>Facebook SDK</li>
            <li>Advertising networks</li>
            <li>Crash reporting services</li>
            <li>Cloud storage services</li>
        </ul>

        <p>Satu-satunya koneksi eksternal adalah ke <strong>server API yang Anda tentukan sendiri</strong>.</p>

        <h2>10. Perubahan Kebijakan Privasi</h2>
        
        <p>Kami dapat memperbarui Kebijakan Privasi ini dari waktu ke waktu. Perubahan akan diberitahukan melalui:</p>
        <ul>
            <li>Update aplikasi di Google Play Store</li>
            <li>Notifikasi di dalam aplikasi (jika perubahan signifikan)</li>
            <li>Halaman kebijakan privasi ini dengan tanggal "Terakhir diperbarui"</li>
        </ul>
        
        <p>Kami menyarankan Anda untuk meninjau Kebijakan Privasi ini secara berkala.</p>

        <h2>11. Dasar Hukum (GDPR)</h2>
        
        <p>Jika Anda berada di Uni Eropa, pemrosesan data Anda didasarkan pada:</p>
        <ul>
            <li><strong>Persetujuan:</strong> Anda memberikan izin akses galeri saat install</li>
            <li><strong>Kepentingan Sah:</strong> Untuk menyediakan fungsi aplikasi yang Anda minta</li>
        </ul>

        <h2>12. Transfer Data Internasional</h2>
        
        <p>Karena Anda menentukan sendiri server tujuan upload:</p>
        <ul>
            <li>Lokasi server bergantung pada API URL yang Anda masukkan</li>
            <li>Kami tidak memiliki kontrol atas lokasi server</li>
            <li>Pastikan server yang Anda gunakan mematuhi regulasi yang berlaku di wilayah Anda</li>
        </ul>

        <h2>13. Cookies dan Tracking</h2>
        
        <p>Aplikasi ini <strong>TIDAK menggunakan</strong>:</p>
        <ul>
            <li>Cookies</li>
            <li>Web beacons</li>
            <li>Tracking pixels</li>
            <li>Fingerprinting</li>
            <li>Analytics tracking</li>
        </ul>

        <h2>14. Kontak</h2>
        
        <div class="contact-info">
            <p>Jika Anda memiliki pertanyaan tentang Kebijakan Privasi ini, silakan hubungi kami:</p>
            <ul>
                <li><strong>Email:</strong> <a href="mailto:support@memora.my.id">support@memora.my.id</a></li>
                <li><strong>Developer:</strong> Memora Development Team</li>
            </ul>
            <p>Kami akan merespons pertanyaan Anda dalam waktu 48 jam.</p>
        </div>

        <h2>15. Pernyataan Akhir</h2>
        
        <p>Dengan menggunakan aplikasi Memora Photo Uploader, Anda menyetujui pengumpulan dan penggunaan informasi sesuai dengan Kebijakan Privasi ini.</p>
        
        <p><strong>Komitmen kami:</strong></p>
        <ul>
            <li>✅ Transparansi penuh tentang penggunaan data</li>
            <li>✅ Tidak ada pengumpulan data tersembunyi</li>
            <li>✅ Privasi Anda adalah prioritas utama</li>
            <li>✅ Kontrol penuh atas data Anda</li>
        </ul>

        <div class="footer">
            <p>&copy; 2025 Memora Photo Uploader. All rights reserved.</p>
            <p>Kebijakan Privasi ini dibuat untuk memenuhi persyaratan Google Play Store dan peraturan privasi yang berlaku.</p>
        </div>
    </div>
</body>
</html>