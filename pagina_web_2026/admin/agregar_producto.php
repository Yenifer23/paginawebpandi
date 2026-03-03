<?php
include("../includes/conexion.php");

if(isset($_POST['guardar'])){

    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];
    $precio = $_POST['precio'];
    $stock = $_POST['stock'];

    // Procesar imagen
    $imagenNombre = $_FILES['imagen']['name'];
    $imagenTemp = $_FILES['imagen']['tmp_name'];

    $rutaDestino = "../uploads/" . $imagenNombre;

    move_uploaded_file($imagenTemp, $rutaDestino);

    $sql = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen)
            VALUES ('$nombre', '$descripcion', '$precio', '$stock', '$imagenNombre')";

    if($conn->query($sql)){
        echo "Producto guardado correctamente";
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Agregar Producto</title>
</head>
<body>

<h2>Agregar Producto</h2>

<form method="POST" enctype="multipart/form-data">
    <input type="text" name="nombre" placeholder="Nombre" required><br><br>
    <textarea name="descripcion" placeholder="Descripción"></textarea><br><br>
    <input type="number" step="0.01" name="precio" placeholder="Precio" required><br><br>
    <input type="number" name="stock" placeholder="Stock" required><br><br>
    <input type="file" name="imagen" accept="image/*" required><br><br>

    <button type="submit" name="guardar">Guardar Producto</button>
</form>

</body>
</html>