<?php
// Jangan ubah apa pun yang ada di file ini.
// Untuk mengetik serial number MMQPC, ketik di file config.php.
// $CFG->mmqpc = 'serial number';

define('CLI_SCRIPT', true);

require('../config.php');

$url = "https://www.mawan.net/moodle/get/token/";
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

$data = json_decode($response);
if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
  echo "Error: String bukan hasil dari json_encode." , PHP_EOL;
  die();
};

if (!isset($data->token)) {
  echo "Error: Tidak ditemukan data token." . PHP_EOL;
  die();
};

if (!ctype_digit($data->token) || (strlen($data->token) != 6)) {
  echo "Error: Token tidak valid. Harus angka sepanjang 6 digit.";
};

$sql = 'update {quiz} set password=? where (length(password)=6) and (unix_timestamp() between timeopen and timeclose)';
$DB->execute($sql, [$data->token]);
