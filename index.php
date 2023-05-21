<?php
// Jangan ubah apa pun yang ada di file ini.
// Untuk mengetik serial number MMQPC, ketik di file config.php.
// $CFG->mmqpc = 'serial number';

define('CLI_SCRIPT', true);

// Cegah aplikasi ini diakses dari web. Harus dari CLI.
require('../config.php');

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
// Query ini berjalan baik pada MySQL dan MariaDB.
$sql = 'update {quiz} set password=? where (length(password)=6) and (unix_timestamp() between timeopen and timeclose)';

// Agar semakin yakin bahwa input hanya berupa angka sepanjang 6 digit, maka digunakan sprintf.
$hasil = $DB->execute($sql, [ sprintf('%06d', (int) $data->token) ]);

if ($hasil == 0) {
  echo "Tidak ada Quiz yang passwordnya diubah." . PHP_EOL;
}
else {
  echo "Sukses! Ada $hasil Quiz yang passwordnya telah diubah." . PHP_EOL;
  echo "Password terbaru adalah $password." . PHP_EOL;
};
