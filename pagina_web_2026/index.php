<?php include("includes/conexion.php"); ?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PANDI MAYORISTA</title>
    <link rel="stylesheet" href="css/estilos.css?v=1.3">
</head>
<body>

<header class="header">
    <div class="logo">
        <img src="uploads/pandilogo2.png" alt="PANDI MAYORISTA">
    </div>

    <div class="brand-box">
        <h1 class="brand-name">PANDI MAYORISTA</h1>
        <nav class="menu">
            <a href="#inicio">INICIO</a>
            <a href="#productos">PRODUCTOS</a>
            <a href="#contacto">CONTACTO</a>
        </nav>
    </div>

    <div class="acciones">
        <a href="registro.php">Crear cuenta</a>
        <a href="carrito.php">🛒</a>
    </div>
</header>

<section id="inicio" class="hero">
    <div class="hero-texto">
        <p>Descubre nuestra</p>
        <h1>COLECCIÓN DESTACADA</h1>
        <a href="#productos" class="btn-hero">Explorar colección</a>
    </div>
</section>

<section id="productos" class="productos-section">
    <div class="productos-grid">
    <?php
    $sql = "SELECT * FROM productos WHERE stock > 0";
    $resultado = $conn->query($sql);
    if($resultado && $resultado->num_rows > 0){
        while($row = $resultado->fetch_assoc()){
    ?>
        <div class="card-producto">
            <img src="uploads/<?php echo $row['imagen']; ?>" alt="">
            <h3><?php echo $row['nombre']; ?></h3>
            <p class="precio">$<?php echo $row['precio']; ?></p>
            <form action="carrito.php" method="POST">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <input type="hidden" name="nombre" value="<?php echo $row['nombre']; ?>">
                <input type="hidden" name="precio" value="<?php echo $row['precio']; ?>">
                <button type="submit" name="agregar" class="btn-agregar">Agregar</button>
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
    <p>WhatsApp: 11-XXXXXXXX</p>
    <p>Email: contacto@pandimayorista.com</p>
</section>

</body>
</html>