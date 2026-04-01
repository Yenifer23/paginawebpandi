<?php session_start();include("includes/conexion.php"); ?>

<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>ZENTRA</title>

<link rel="stylesheet" href="css/estilos.css?v=3">

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
    <div class="carrito-icon" onclick="toggleCarrito()">
        🛒
        <span class="contador-carrito" id="contadorCarrito">0</span>
    </div>
</div>

</header>

<!-- DRAWER DEL CARRITO -->
<div id="drawerCarrito" class="drawer-carrito">

<div class="drawer-header">
<h2>Tu carrito</h2>
<span onclick="toggleCarrito()" class="cerrar">✖</span>
</div>

<div class="drawer-body" id="contenidoCarrito">
<p>Tu carrito está vacío</p>
</div>

<div class="drawer-footer">
<h3>Total: $0</h3>
<a href="carrito.php" class="btn-confirmar">Confirmar pedido</a>
</div>

</div>

<!-- MENU CATEGORIAS -->

<div id="categorias" class="menu-categorias">

<button onclick="filtrar('todo')">TODO</button>
<button onclick="filtrar('femenina')">Indumentaria femenina</button>
<button onclick="filtrar('masculina')">Indumentaria masculina</button>
<button onclick="filtrar('calzado')">Calzado</button>
<button onclick="filtrar('deportivo')">Deportivo</button>

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

// ⭐ MOSTRAR TODOS LOS PRODUCTOS (aunque stock sea 0)
$sql = "SELECT * FROM productos";
$resultado = $conn->query($sql);

if($resultado && $resultado->num_rows > 0){

while($row = $resultado->fetch_assoc()){

$categoria = isset($row['categoria']) ? $row['categoria'] : "todo";

?>

<div class="card-producto" data-categoria="<?php echo $categoria; ?>">

<img src="uploads/<?php echo $row['imagen']; ?>" alt="">

<h3><?php echo $row['nombre']; ?></h3>

<p class="precio">$<?php echo $row['precio']; ?></p>

<?php if($row['stock'] > 0){ ?>

<form class="form-agregar">
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
<input type="hidden" name="nombre" value="<?php echo $row['nombre']; ?>">
<input type="hidden" name="precio" value="<?php echo $row['precio']; ?>">
<input type="hidden" name="imagen" value="<?php echo $row['imagen']; ?>">

<button type="submit" name="agregar" class="btn-agregar">
Agregar
</button>
</form>

<?php } else { ?>

<p style="color:red;font-weight:bold;font-size:18px;">
NO HAY STOCK
</p>

<?php } ?>

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


<!-- SCRIPTS -->
<script>
document.addEventListener("DOMContentLoaded", function(){

/* ABRIR / CERRAR CARRITO */
window.toggleCarrito = function(){
    document.getElementById("drawerCarrito").classList.toggle("activo");
}

window.abrirCarrito = function(){
    document.getElementById("drawerCarrito").classList.add("activo");
}

window.cerrarCarrito = function(){
    document.getElementById("drawerCarrito").classList.remove("activo");
}

/* CERRAR AL HACER CLICK AFUERA */
document.addEventListener("click", function(e){

    let drawer = document.getElementById("drawerCarrito");
    let boton = document.querySelector(".carrito-icon");

    if(
        drawer.classList.contains("activo") &&
        !drawer.contains(e.target) &&
        !boton.contains(e.target)
    ){
        cerrarCarrito();
    }

});

/* EVITAR QUE SE CIERRE AL HACER CLICK DENTRO */
document.getElementById("drawerCarrito").addEventListener("click", function(e){
    e.stopPropagation();
});

/* AGREGAR PRODUCTOS */
document.querySelectorAll(".form-agregar").forEach(form => {

form.addEventListener("submit", function(e){
e.preventDefault();

let data = new FormData(this);
data.append("accion", "agregar");

fetch("ajax_carrito.php", {
method: "POST",
body: data
})
.then(res => res.json())
.then(data => {
actualizarCarrito(data);
abrirCarrito();
});

});

});

});

/* ACTUALIZAR UI */
function actualizarCarrito(data){

let contenedor = document.getElementById("contenidoCarrito");
let totalHTML = document.querySelector(".drawer-footer h3");
let contador = document.getElementById("contadorCarrito");

contenedor.innerHTML = "";

if(data.carrito.length === 0){
contenedor.innerHTML = "<p>Tu carrito está vacío</p>";
return;
}

data.carrito.forEach((item, index) => {

contenedor.innerHTML += `
<div class="item-carrito">
<img src="uploads/${item.imagen}" width="50">

<div>
<p>${item.nombre}</p>
<p>$${item.precio} x ${item.cantidad}</p>
</div>

<div class="controles">
<button onclick="restarItem(${index})">➖</button>
<span>${item.cantidad}</span>
<button onclick="sumarItem(${index})">➕</button>
</div>

<button onclick="eliminarItem(${index})">❌</button>
</div>
`;

});

totalHTML.innerText = "Total: $" + data.total;
contador.innerText = data.cantidad;

}

/* FUNCIONES AJAX */

function eliminarItem(index){
fetch("ajax_carrito.php", {
method: "POST",
headers: {"Content-Type": "application/x-www-form-urlencoded"},
body: "accion=eliminar&index=" + index
})
.then(res => res.json())
.then(data => actualizarCarrito(data));
}

function sumarItem(index){
fetch("ajax_carrito.php", {
method: "POST",
headers: {"Content-Type": "application/x-www-form-urlencoded"},
body: "accion=sumar&index=" + index
})
.then(res => res.json())
.then(data => actualizarCarrito(data));
}

function restarItem(index){
fetch("ajax_carrito.php", {
method: "POST",
headers: {"Content-Type": "application/x-www-form-urlencoded"},
body: "accion=restar&index=" + index
})
.then(res => res.json())
.then(data => actualizarCarrito(data));
}
</script>

</body>
</html>