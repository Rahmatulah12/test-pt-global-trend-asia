<?php
$randomNumber =  rand(1, 100);
    
if($randomNumber % 5 == 0) {
    
    if($randomNumber <= 60) {
        echo "Kurang";
    } else if($randomNumber > 60 && $randomNumber <= 70) {
        echo "Cukup";
    } else if($randomNumber > 70 && $randomNumber <= 80) {
        echo "Baik";
    } else if($randomNumber > 80) {
        echo "Luar Biasa";
    } else if($randomNumber > 100) {
        echo "Nilai Diluar Batas";
    }

} else {
	echo "Bukan Bilangan Kelipatan 5";
}