# mmqpc
Mawan's Moodle Quiz Password Changer  

Dasar pemikiran:  
Quiz pada Moodle bisa dipasangi password, tapi password itu tidak bisa berubah otomatis. Saya ingin agar password diubah misalkan tiap 5 menit. Perubahan password itu bisa diketahui oleh pengawas ujian dari ponselnya yang telah dipasangi aplikasi MMQPC. Aplikasi ini dapat diunduh di Google Play.

`https://play.google.com/store/apps/details?id=appinventor.ai_mawan911.MMQPC`

Aplikasi Android ini bahkan tetap bisa berfungsi sekali pun tidak mendapat sinyal, karena aplikasi MMQPC tidak membutuhkan koneksi internet.

Sedangkan script PHP ini harus dipasang di server Moodle. Script ini hanya mengubah password Quiz yang telah dipasangi password sepanjang 6 karakter. Jadi misalkan ada Quiz dengan password 'rahasia' maka password tersebut tidak akan diubah sebab panjang passwordnya adalah 7 karakter. Quiz yang tidak dipasangi password pun tidak akan diubah passwordnya.

Petunjuk instalasi:  

* `cd /path/ke/moodle`
* `git clone https://github.com/mawann/mmqpc`

Untuk meng-update script ini ke versi terbaru, lakukan:

* `cd /path/ke/moodle`
* `cd mmqpc`
* `git pull`

Untuk membuat Quiz:

* Login ke Moodle.
* Buat Quiz.
* Pada Quiz Setting, cari settingan berjudul "Extra restrictions on attempts".
* Isikan password dengan ketentuan: **Harus angka semua, sebanyak 6 angka.**
* Klik "Save and Display".

Script ini berjalan pada mode evaluasi. Untuk mengetik serial number, buka file konfigurasi Moodle yang bernama `config.php`, kemudian tambahkan kode ini:

`$CFG->mmqpc = 'serial number';`

Serial number bisa diminta ke Mawan.

Jalankan script ini setiap menit, misalkan:  
`* * * * * php /path/ke/moodle/mmqpc/ >/dev/null 2>&1`
