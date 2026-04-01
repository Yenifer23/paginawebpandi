<?php
session_start();
header('Content-Type: application/json');
error_reporting(0);
ini_set('display_errors', 0);

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

$accion = $_POST['accion'] ?? '';

/* ➕ AGREGAR */
if($accion == "agregar"){

    $id = $_POST['id'];
    $existe = false;

    foreach($_SESSION['carrito'] as &$item){
        if($item['id'] == $id){
            $item['cantidad']++;
            $existe = true;
            break;
        }
    }

    if(!$existe){
        $_SESSION['carrito'][] = [
            "id" => $_POST['id'],
            "nombre" => $_POST['nombre'],
            "precio" => $_POST['precio'],
            "imagen" => $_POST['imagen'],
            "cantidad" => 1
        ];
    }
}

/* ❌ ELIMINAR */
if($accion == "eliminar"){
    $index = $_POST['index'];
    unset($_SESSION['carrito'][$index]);
    $_SESSION['carrito'] = array_values($_SESSION['carrito']);
}

/* ➕ SUMAR */
if($accion == "sumar"){
    $_SESSION['carrito'][$_POST['index']]['cantidad']++;
}

/* ➖ RESTAR */
if($accion == "restar"){
    $i = $_POST['index'];

    if($_SESSION['carrito'][$i]['cantidad'] > 1){
        $_SESSION['carrito'][$i]['cantidad']--;
    } else {
        unset($_SESSION['carrito'][$i]);
        $_SESSION['carrito'] = array_values($_SESSION['carrito']);
    }
}

/* RESPUESTA */
$total = 0;
$cantidadTotal = 0;

foreach($_SESSION['carrito'] as $item){
    $total += $item['precio'] * $item['cantidad'];
    $cantidadTotal += $item['cantidad'];
}

echo json_encode([
    "carrito" => array_values($_SESSION['carrito']),
    "total" => $total,
    "cantidad" => $cantidadTotal
]);