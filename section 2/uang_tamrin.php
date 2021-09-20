<?php 
$tahunAwal = 2017;
    $tabunganAwal = 10000000;
    $tahunAkhir = 2032;
    $totalWaktu = $tahunAkhir - $tahunAwal;
    $bunga = 0.05;

    $totalBunga = ($tabunganAwal * $bunga) * $totalWaktu;
    $hasil = $tabunganAwal + $totalBunga;
    echo "Rp. $hasil";
