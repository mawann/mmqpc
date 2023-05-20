# mmqpc
Mawan's Moodle Quiz Password Changer  

Dengan MOQCA (juga dikenal dengan MMQPC atau Mawan's Moodle Quiz Password Changer), maka password pada quiz dapat diubah secara berkala, secara otomatis. Ini berguna untuk mencegah peserta ujian keluar masuk quiz (misalkan untuk mencontek di peramban web).

MOQCA terdiri:
1. Aplikasi Android yang dipasang di ponsel setiap pengawas ujian.
2. Script PHP yang dipasang di server Moodle.

Aplikasi Andoid dapat diunduh di Google Play Store:
https://play.google.com/store/apps/details?id=appinventor.ai_mawan911.MMQPC

Script PHP dapat diunduh atau di-clone dari:
https://github.com/mawann/mmqpc/

Anda boleh menggunakan MOQCA sepuasnya, selamanya. Tapi ada batasannya yaitu:
1. Salt tidak bisa diubah, yaitu garam-dapur.
2. Durasi penggantian tidak bisa diubah, yaitu 5 menit.

Untuk dapat mengubah dua parameter di atas, anda harus melakukan registrasi.

A. Petunjuk instalasi aplikasi Android di ponsel setiap pengawas ujian:

Buka Google Play. Cari aplikasi yang bernama MMQPC. Lakukan pemasangan sampai selesai.

B. Petunjuk instalasi di server Moodle:

* Masuk ke www-root dari Moodle: `cd /path/ke/moodle`
* `git clone https://github.com/mawann/mmqpc`

Untuk meng-update script ini ke versi terbaru, lakukan:

* `cd /path/ke/moodle`
* `cd mmqpc`
* `git pull`

Anda juga bisa memasang script secara manual (tidak disarankan) yaitu:

* Dengan memakai _File Manager_ atau _File Explorer_, buka folder www-root milik Moodle. Untuk patokan, pada www-root ada file config.php milik Moodle.
* Buat folder bernama `mmqpc` tepat di bawah folder www-root.
* Salin kode `index.php` pada Github, kemudian disimpan di folder mmqpc.

Untuk membuat Quiz di Moodle:

* Login ke Moodle.
* Buat Quiz.
* Pada Quiz Setting, cari settingan berjudul "Extra restrictions on attempts".
* Isikan password dengan ketentuan: **Harus angka semua, sebanyak 6 angka.**
* Klik "Save and Display".

Script ini berjalan pada mode evaluasi.  
Pada mode evaluasi, salt adalah `garam-dapur` dan interval adalah 5 menit.  
Untuk mengubah settingan ini, anda harus melakukan registrasi dan akan diberi serial number.

Jalankan script ini setiap menit, misalkan:  
`* * * * * php /path/ke/moodle/mmqpc/ >/dev/null 2>&1`
