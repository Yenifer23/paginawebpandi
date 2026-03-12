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
<html>
<head>
    <title>Carrito</title>
    <link rel="stylesheet" href="css/estilos.css">
</head>
<body>

<h1 style="padding:20px;">Tu Carrito</h1>

<div style="padding:20px;">

<?php if(!empty($_SESSION['carrito'])){ ?>

<table border="1" cellpadding="10">
<tr>
    <th>Producto</th>
    <th>Precio</th>
    <th>Acción</th>
</tr>

<?php foreach($_SESSION['carrito'] as $index => $item){ 
    $total += $item['precio'];
?>
<tr>
    <td><?php echo $item['nombre']; ?></td>
    <td>$<?php echo $item['precio']; ?></td>
    <td>
        <a href="carrito.php?eliminar=<?php echo $index; ?>">Eliminar</a>
    </td>
</tr>
<?php } ?>

</table>

<h2>Total: $<?php echo $total; ?></h2>

<a href="finalizar_pedido.php" style="background:#E91E63;color:white;padding:10px 20px;text-decoration:none;">
Finalizar Pedido
</a>

<?php } else { ?>
<p>El carrito está vacío</p>
<?php } ?>

</div>

</body>
</html>