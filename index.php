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
}

// Tutup CURL
curl_close($ch);

echo "Respon Mawan.net adalah:" . PHP_EOL;
echo $response;
echo PHP_EOL;
