<?php
// +---------------------------------------------------------------+
// | Mawan's Moodle Quiz Password Changer versi 2.0                |
// | (C) Mawan Agus Nugroho, 2023.                                 |
// | Source: https://github.com/mawann/mmqpc                       |
// |                                                               |
// | Jangan ubah apa pun yang ada di file ini.                     |
// | Untuk mengetik serial number MMQPC, ketik di file config.php. |
// | Contoh isi file config.php: $CFG->mmqpc = 'serial number';    |
// +---------------------------------------------------------------+

// Cegah aplikasi ini diakses dari web. Harus dari CLI.
define('CLI_SCRIPT', true);

// Folder script ini harus disimpan satu tingkat di bawah config.php.
// Misalkan:  config.php ada di folder /home/mawan/example.com/public_html
// maka script ini harus ada di folder /home/mawan/example.com/public_html/mmqpc
$namaConfig = dirname(__DIR__) . '/config.php';
if (file_exists($namaConfig)) {
  require($namaConfig);
}
else {
  echo 'Error: File config.php tidak berhasil ditemukan di parent folder.' . PHP_EOL;
  die();
};

// Harus ada variabel $CFG dan $DB. Bila tidak ada, berarti itu bukan filenya Moodle.
if (!isset($CFG) || !isset($DB)) {
  echo 'Error: File config.php di www root sepertinya bukan berisi konfigurasi Moodle.' . PHP_EOL;
  die();
};

// Server penghasil token, yaitu Mawan.net
$url = "https://www.mawan.net/moodle/get/token/";

// Data yang akan dikirim ke Mawan.net
$wwwroot = $CFG->wwwroot;
$mmqpc = isset($CFG->mmqpc) ? $CFG->mmqpc : 'tes';
$password = password_hash($wwwroot . '/' . $mmqpc, PASSWORD_DEFAULT);

// Data yang ingin dikirim
$postData = array(
  'wwwroot' => $wwwroot,
  'password' => $password
);

// Inisialisasi CURL
$ch = curl_init($url);

// Set opsi CURL
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Eksekusi CURL
$response = curl_exec($ch);

// Cek jika ada kesalahan
if(curl_errno($ch)){
  echo 'Error: ' . curl_error($ch);
  die();
};

// Tutup CURL
curl_close($ch);

// Tanggapan dari Mawan.net dicoba diubah kembali dari string JSON menjadi objek.
$data = json_decode($response);

// Tanggapan dari Mawan.net harus berupa string JSON, yang bisa diubah menjadi objek.
// Bila tidak bisa diubah, mungkin string biasa (tulisan error).
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
  echo "Error: String bukan hasil dari json_encode." . PHP_EOL;
  echo "Tanggapan dari Mawan.net adalah:" . PHP_EOL;
  // Sebenarnya tidak perlu memakai htmlentities, karena script ini dijalankan dari CLI.
  // Tapi untuk sekedar kehati-hatian (mitigasi), maka dipakai fungsi ini.
  echo htmlentities($response) . PHP_EOL;
  die();
};

// Tanggapan dari Mawan.net harus ada data bernama token.
if (!isset($data->token)) {
  echo "Error: Tidak ditemukan data token." . PHP_EOL;
  die();
};

// Token harus berupa 6 digit angka.
if (!ctype_digit($data->token) || (strlen($data->token) != 6)) {
  echo "Error: Token tidak valid. Harus angka sepanjang 6 digit.";
  die();
};

// Lakukan perubahan password Quiz hanya untuk Quiz yang sedang aktif berjalan.
// Query ini berjalan baik pada PostgreSQL, MySQL dan MariaDB.
$epoch = (isset($CFG->dbtype) && ($CFG->dbtype == 'pgsql')) ? 'extract(epoch from current_timestamp)' : 'unix_timestamp()';
$sql = 'update {quiz} set password=? where (length(password)=6) and (' . $epoch . ' between timeopen and timeclose)';

// Agar semakin yakin bahwa input hanya berupa angka sepanjang 6 digit, maka digunakan sprintf.
$hasil = $DB->execute($sql, [ sprintf('%06d', (int) $data->token) ]);

if ($hasil == false) {
  echo "Error: Gagal melakukan query untuk mengubah password Quiz." . PHP_EOL;
}
else {
  echo "Sukses melakukan update password Quiz." . PHP_EOL;
  echo "Password terbaru adalah $data->token." . PHP_EOL;
};
