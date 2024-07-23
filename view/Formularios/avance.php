<?php
$color = "";
//SE CALCULA EL AVANCE EN PORCENTAJE, SEGÚN EL PASO ACTUAL EN QUE SE ENCUENTRE
if($row["cantidad_pasos"]>0){
    $avance= round(100 * $row["paso"] / $row["cantidad_pasos"]);
}
else{
    $avance = 0;
}

if($avance >= 0 && $avance < 25){
    $color = "-danger";
}
elseif($avance >= 25 && $avance < 50){
    $color = "-warning";
}
elseif($avance >= 50 && $avance < 75){
    $color = "-success";
}
elseif($avance >= 75 && $avance < 100){
    $color = "-success";
}
?>