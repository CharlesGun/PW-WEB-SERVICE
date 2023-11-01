<?php
# generatejson1.php
# Format Data: JSON
header('Content-type: application/json');
# Buat variabel penampung array
$arr = array();
$arr = array("nama" => "Taman Kupu-Kupu Gita Persada", "alamat" => "Gunung Betung, Lampung");
# Format Data: JSON
echo json_encode($arr);
?>