# mmqpc
Mawan's Moodle Quiz Password Changer  

Petunjuk instalasi:  

* `cd /path/ke/moodle`
* `git clone https://github.com/mawann/mmqpc`
* `cd mmqpc`
* `git pull`

Untuk meng-update kode, lakukan:
* `cd /path/ke/moodle`
* `cd mmqpc`
* `git pull`

Script ini berjalan pada mode evaluasi. Untuk mengetik serial number, buka file konfigurasi Moodle yang bernama `config.php`, kemudian tambahkan kode ini:

`$CFG->mmqpc = 'serial number';`

Serial number bisa diminta ke Mawan.

Jalankan script ini setiap menit, misalkan:  
`* * * * * php /path/ke/moodle/mmqpc/ >/dev/null 2>&1`
