<?php
session_start();

if(!isset($_SESSION['carrito'])){
    $_SESSION['carrito'] = [];
}

if(isset($_POST['agregar'])){

    $producto = [
        "id" => $_POST['id'],
        "nombre" => $_POST['nombre'],
        "precio" => $_POST['precio'],
        "cantidad" => 1
    ];

    $_SESSION['carrito'][] = $producto;
}

if(isset($_GET['eliminar'])){
    unset($_SESSION['carrito'][$_GET['eliminar']]);
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Carrito | ZENTRA</title>

<link rel="stylesheet" href="css/carrito.css">

<script src="js/carrito.js" defer></script>

</head>

<body>

<div class="carrito-container">

<h1>🛒 Tu carrito</h1>

<?php if(!empty($_SESSION['carrito'])){ ?>

<div class="tabla-carrito">

<div class="fila encabezado">
<div>Producto</div>
<div>Precio</div>
<div>Acción</div>
</div>

<?php foreach($_SESSION['carrito'] as $index => $item){

$total += $item['precio'];

?>

<div class="fila">

<div class="producto">
<?php echo $item['nombre']; ?>
</div>

<div class="precio">
$<?php echo $item['precio']; ?>
</div>

<div>
<a class="btn-eliminar" href="carrito.php?eliminar=<?php echo $index; ?>">
Eliminar
</a>
</div>

</div>

<?php } ?>

</div>

<div class="resumen">

<h2>Total: $<?php echo $total; ?></h2>

<a class="btn-finalizar" href="finalizar_pedido.php">
Finalizar Pedido
</a>

</div>

<?php } else { ?>

<div class="carrito-vacio">

<p>Tu carrito está vacío</p>

<a href="index.php" class="btn-volver">
Ver productos
</a>

</div>

<?php } ?>

</div>

</body>

</html>