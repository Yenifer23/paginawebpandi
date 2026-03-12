<?php
include("../includes/conexion.php");

if(isset($_POST['guardar'])){

$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];
$precio = $_POST['precio'];
$stock = $_POST['stock'];

$imagenNombre = $_FILES['imagen']['name'];
$imagenTemp = $_FILES['imagen']['tmp_name'];

$rutaDestino = "../uploads/" . $imagenNombre;

move_uploaded_file($imagenTemp, $rutaDestino);

$sql = "INSERT INTO productos (nombre, descripcion, precio, stock, imagen)
VALUES ('$nombre','$descripcion','$precio','$stock','$imagenNombre')";

if($conn->query($sql)){
$mensaje = "Producto guardado correctamente";
}else{
$mensaje = "Error: " . $conn->error;
}

}
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Administrador | Agregar Producto</title>

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
}

body{
font-family:'Segoe UI',sans-serif;
background:#6b4f3a;
display:flex;
justify-content:center;
align-items:center;
height:100vh;
}

/* CONTENEDOR */

.admin-container{
background:white;
padding:40px;
border-radius:10px;
width:400px;
box-shadow:0 10px 30px rgba(0,0,0,0.2);
}

/* TITULO */

.admin-container h2{
margin-bottom:20px;
color:#6b4f3a;
text-align:center;
}

/* INPUTS */

.admin-container input,
.admin-container textarea{

width:100%;
padding:12px;
margin-bottom:15px;
border:1px solid #ddd;
border-radius:6px;
font-size:14px;

}

/* BOTON */

.admin-container button{

width:100%;
padding:12px;
background:#b08968;
border:none;
color:white;
font-size:16px;
border-radius:6px;
cursor:pointer;

}

.admin-container button:hover{
background:#9c7556;
}

/* MENSAJE */

.mensaje{
background:#e6f4ea;
color:#2e7d32;
padding:10px;
margin-bottom:15px;
border-radius:5px;
text-align:center;
}

/* RESPONSIVE */

@media(max-width:500px){

.admin-container{
width:90%;
padding:30px;
}

}

</style>

</head>

<body>

<div class="admin-container">

<h2>Agregar Producto</h2>

<?php
if(isset($mensaje)){
echo "<div class='mensaje'>$mensaje</div>";
}
?>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="nombre" placeholder="Nombre del producto" required>

<textarea name="descripcion" placeholder="Descripción"></textarea>

<input type="number" step="0.01" name="precio" placeholder="Precio" required>

<input type="number" name="stock" placeholder="Stock" required>

<input type="file" name="imagen" accept="image/*" required>

<button type="submit" name="guardar">
Guardar Producto
</button>

</form>

</div>

</body>
</html>