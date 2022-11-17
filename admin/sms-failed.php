<?php
include_once 'database/connect.php';

$id = $_GET['tid_tanggung'];

$select = $pdo->prepare("SELECT * FROM tuntut_tanggungan INNER JOIN ahli_kariah ON tuntut_tanggungan.kariah_id = ahli_kariah.kariah_id WHERE tuntut_tanggungan.tid_tanggung = $id");
$select->execute();
$row = $select->fetch(PDO::FETCH_ASSOC);
$tel_hp = $row['tel_hp'];
$nama = $row['nama'];

$form_data = [
    'token_uid' => "746210583",
    'token_key' => "utwe2qsd5r7acgvx8ozi",
    'receipients' => $tel_hp,
    'message' => "Masjid Al-Wustha: Pembayaran tuntutan bagi si mati yang bernama $nama gagal. Sila hubungi pihak masjid untuk semakan semula. Terima kasih"
];

$curl = curl_init();
curl_setopt($curl, CURLOPT_POST, 1);
curl_setopt($curl, CURLOPT_URL, 'https://sms.ala.my/api/v1/send');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POSTFIELDS, $form_data);
$result = curl_exec($curl);
$obj = json_decode($result);

$update_kariah = $pdo->prepare("UPDATE tuntut_tanggungan SET status = 'Gagal' WHERE tid_tanggung = $id");
$update_kariah->execute();

header('Location: tuntutan-tanggungan.php');
