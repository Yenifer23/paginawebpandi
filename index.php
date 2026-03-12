<?php include("includes/conexion.php"); ?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>ZENTRA</title>

<link rel="stylesheet" href="css/estilos.css?v=2">

<script>

function toggleCategorias(){

let menu = document.getElementById("categorias");

if(menu.style.display === "flex"){
menu.style.display = "none";
}else{
menu.style.display = "flex";
}

}

</script>

</head>

<body>

<header class="header">

<div class="logo">
<img src="uploads/logo1.png">
</div>

<div class="brand-box">

<h1 class="brand-name">ZENTRA</h1>

<nav class="menu">
<a href="#inicio">INICIO</a>
<a href="#productos">PRODUCTOS</a>
<a href="#contacto">CONTACTO</a>
</nav>

</div>

<div class="acciones">
<div class="menu-icon" onclick="toggleCategorias()">☰</div>
</div>

</header>

<!-- MENU CATEGORIAS -->

<div id="categorias" class="menu-categorias">

<button onclick="filtrar('todo')">TODO</button>

<button onclick="filtrar('femenina')">
Indumentaria femenina
</button>

<button onclick="filtrar('masculina')">
Indumentaria masculina
</button>

<button onclick="filtrar('calzado')">
Calzado
</button>

<button onclick="filtrar('deportivo')">
Deportivo
</button>

</div>

<section id="inicio" class="hero">

<div class="hero-texto">

<p>Descubre nuestra</p>
<h1>COLECCIÓN DESTACADA</h1>

</div>

</section>

<section id="productos" class="productos-section">

<div class="productos-grid">

<?php

$sql = "SELECT * FROM productos WHERE stock > 0";
$resultado = $conn->query($sql);

if($resultado && $resultado->num_rows > 0){

while($row = $resultado->fetch_assoc()){

$categoria = isset($row['categoria']) ? $row['categoria'] : "todo";

?>

<div class="card-producto" data-categoria="<?php echo $categoria; ?>">

<img src="uploads/<?php echo $row['imagen']; ?>" alt="">

<h3><?php echo $row['nombre']; ?></h3>

<p class="precio">$<?php echo $row['precio']; ?></p>

<form action="carrito.php" method="POST">

<input type="hidden" name="id" value="<?php echo $row['id']; ?>">

<input type="hidden" name="nombre" value="<?php echo $row['nombre']; ?>">

<input type="hidden" name="precio" value="<?php echo $row['precio']; ?>">

<button type="submit" name="agregar" class="btn-agregar">
Agregar
</button>

</form>

</div>

<?php

}

}

?>

</div>

</section>

<section id="contacto" class="contacto">

<h2>CONTACTO</h2>

<p>WhatsApp: 3804269768</p>

<p>Email: zentra@gmail.com</p>

</section>

<!-- BOTON WHATSAPP -->

<a href="https://wa.me/5493804269768?text=Hola%20quiero%20hacer%20una%20consulta"
class="whatsapp-btn"
target="_blank">

<img src="https://cdn-icons-png.flaticon.com/512/220/220236.png">

</a>

<script>

function filtrar(categoria){

let productos = document.querySelectorAll(".card-producto");

productos.forEach(function(prod){

if(categoria === "todo"){

prod.style.display = "block";

}
else if(prod.dataset.categoria === categoria){

prod.style.display = "block";

}
else{

prod.style.display = "none";

}

});

}

</script>

</body>

</html>