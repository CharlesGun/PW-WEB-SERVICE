<?php
$user;
if (!isset($user)) {
    echo "Variabel tidak ada/belum terbentuk";
} elseif (empty($user)) {
    echo "Variabel ada tapi kosong";
} else {
    echo "Variabel ada dan berisi";
}
?>

<?php
$tahun = date ("Y");
$kabisat = ($tahun%4 == 0) ? "KABISAT" : "BUKAN KABISAT";
echo "<br>Tahun <b>$tahun</b> $kabisat";
?>