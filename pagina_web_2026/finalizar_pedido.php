<?php
session_start();

if(empty($_SESSION['carrito'])){
    header("Location: index.php");
    exit();
}

$mensaje = "Hola, quiero hacer el siguiente pedido:%0A%0A";
$total = 0;

foreach($_SESSION['carrito'] as $item){
    $mensaje .= "- " . $item['nombre'] . " $" . $item['precio'] . "%0A";
    $total += $item['precio'];
}

$mensaje .= "%0ATotal: $" . $total;

$telefono = "3804777943"; // CAMBIAR POR TU NUMERO

$link = "https://wa.me/".$telefono."?text=".$mensaje;

$_SESSION['carrito'] = [];

header("Location: $link");
exit();
?>