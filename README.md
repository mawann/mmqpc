# MOQCA / MMQPC
_Mawan's Moodle Quiz Password Changer_

Murid zaman sekarang makin kréatif dalam mencari celah keamanan. Meréka bisa keluar dari _Safe Exam Browser_ atau aplikasi sejenis Kiosk Mode untuk browsing mencari jawaban di internét, setelah itu masuk kembali ke Moodle dan melanjutkan Quiz.

> Untuk mencegah hal ini, guru dapat memasang password pada Quiz kemudian secara berkala (misalkan tiap 5 menit) password itu diubah. Murid yang keluar dari Quiz (untuk menconték) dan masuk kembali ke Quiz pasti kesulitan karena password telah diubah.

Tapi tentu saja ini merépotkan guru karena password Quiz diubah secara manual. Adakah cara yang lebih mudah dan otomatis?
Ada! Yaitu gunakan Mawan's Moodle Quiz Password Changer.

**Intinya adalah:**

1. Guru (pengawas ujian) memasang aplikasi Android di ponsélnya. Aplikasi ini untuk memunculkan password. Tidak perlu konéksi internét! Dengan demikian, aplikasi ini masih dapat berjalan lancar di lab komputer yang sulit mendapat sinyal.
2. Admin Moodle memasang satu script PHP di Moodle. Script ini dapat diunduh secara manual (tidak disarankan) atau otomatis di-clone dengan perintah `git clone` (disarankan).

Dengan MOQCA (juga dikenal dengan MMQPC atau _Mawan's Moodle Quiz Password Changer_), maka password pada quiz dapat diubah secara berkala, secara otomatis. Ini berguna untuk mencegah peserta ujian keluar masuk quiz (misalkan untuk mencontek di peramban web).

## Penjelasan

MOQCA terdiri:
1. Aplikasi Android yang dipasang di ponsel setiap pengawas ujian.
2. Script PHP yang dipasang di server Moodle.

Aplikasi Andoid dapat diunduh di Google Play Store:  
https://play.google.com/store/apps/details?id=appinventor.ai_mawan911.MMQPC

Script PHP dapat diunduh atau di-clone dari:  
https://github.com/mawann/mmqpc/

Anda boleh menggunakan MOQCA sepuasnya, selamanya. Tapi ada batasannya yaitu:
1. `Salt` tidak bisa diubah, yaitu garam-dapur.
2. `Duration` penggantian tidak bisa diubah, yaitu password berubah tiap 5 menit.

Untuk dapat mengubah dua parameter di atas, anda harus melakukan registrasi.  
Dengan mengubah dua parameter itu, password yang dihasilkan pun berbeda.

## Petunjuk instalasi aplikasi Android di ponsel setiap pengawas ujian:

Buka Google Play. Cari aplikasi yang bernama MMQPC. Lakukan pemasangan sampai selesai.

## Petunjuk instalasi di server Moodle:

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

Jalankan script PHP ini setiap menit, misalkan:  
`* * * * * php /path/ke/moodle/mmqpc/ >/dev/null 2>&1`

Atau tiap 5 menit:  
`*/5 * * * * php /path/ke/moodle/mmqpc/ >/dev/null 2>&1`

## Untuk membuat Quiz di Moodle:

* Login ke Moodle.
* Buat Quiz.
* Pada Quiz Setting, cari settingan berjudul _"Extra restrictions on attempts"_.
* Isikan password dengan ketentuan: **Harus angka semua, sebanyak 6 angka.** Anda boleh mengetik `000000` atau `123456`. Nanti pada saatnya, angka ini akan diganti oleh script secara otomatis.
* Klik "Save and Display".

## Registrasi

Script ini berjalan pada mode evaluasi.  
Pada mode evaluasi, salt adalah `garam-dapur` dan interval adalah `5` menit.  
Untuk mengubah settingan ini, anda harus melakukan registrasi dan akan diberi serial number.
