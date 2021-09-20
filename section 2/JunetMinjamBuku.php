<?php

$batas = 10;

$denda1 = 2000;
$denda2 = 5000;
$denda3 = 10000;

$totalPinjam1 = 15;
$totalPinjam2 = 17;
$totalPinjam3 = 21;

if($totalPinjam1 > $batas) {
    $totalHariDenda = $totalPinjam1 - $batas;
    $totalDenda = $denda1 * $totalHariDenda;
    echo "Kamu melewati $totalHariDenda hari, dendanya : Rp. $totalDenda";
} else {
    echo "aman";
}
echo "<br />";
if($totalPinjam2 > $batas) {
    $totalHariDenda2 = $totalPinjam2 - $batas;
    $totalDenda2 = $denda2 * $totalHariDenda;
    echo "Kamu melewati $totalHariDenda2 hari, dendanya : Rp. $totalDenda2";
} else {
    echo "aman";
}
echo "<br />";
if($totalPinjam3 > $batas) {
    $totalHariDenda3 = $totalPinjam3 - $batas;
    $totalDenda3 = $denda3 * $totalHariDenda;
    echo "Kamu melewati $totalHariDenda3 hari, dendanya : Rp. $totalDenda3";
} else {
    echo "aman";
}