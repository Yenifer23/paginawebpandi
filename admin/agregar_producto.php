<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit;
}

error_reporting(0);
ini_set('display_errors', 0);

include("../includes/conexion.php");

/* --- LÓGICA DE OPERACIONES --- */

// GUARDAR NUEVO PRODUCTO
if(isset($_POST['guardar'])){
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $id_categoria = $_POST['id_categoria'] ?? 0;
    $disponible = $_POST['disponible'] ?? 1;
    $imagen = "";

    $stock = $disponible == 1 ? 1 : 0;

    if(isset($_FILES['imagen']) && $_FILES['imagen']['name']!=""){
        $imagen = $_FILES['imagen']['name'];
        $tmp = $_FILES['imagen']['tmp_name'];
        move_uploaded_file($tmp,"../uploads/".$imagen);
    }

    $conn->query("INSERT INTO productos (nombre,descripcion,precio,id_categoria,imagen,disponible,stock) 
                  VALUES ('$nombre','$descripcion','$precio','$id_categoria','$imagen','$disponible','$stock')");
    header("Location: agregar_producto.php");
    exit;
}

// ELIMINAR PRODUCTO
if(isset($_GET['eliminar'])){
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM productos WHERE id=$id");
    header("Location: agregar_producto.php");
    exit;
}

// CAMBIAR ESTADO RÁPIDO
if(isset($_POST['cambiar_estado'])){
    $id = intval($_POST['id']);
    $estado = intval($_POST['estado']);
    $stock = $estado == 1 ? 1 : 0;

    $conn->query("UPDATE productos SET disponible=$estado, stock=$stock WHERE id=$id");
    header("Location: agregar_producto.php");
    exit;
}

// EDITAR (Cargar datos al formulario)
$editar = null;
if(isset($_GET['editar'])){
    $idEditar = intval($_GET['editar']);
    $res = $conn->query("SELECT * FROM productos WHERE id=$idEditar");
    if($res && $res->num_rows>0){
        $editar = $res->fetch_assoc();
    }
}

// ACTUALIZAR PRODUCTO EXISTENTE
if(isset($_POST['actualizar'])){
    $id = intval($_POST['id']);
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $precio = $_POST['precio'] ?? 0;
    $id_categoria = $_POST['id_categoria'] ?? 0;

    if(isset($_FILES['imagen']) && $_FILES['imagen']['name']!=""){
        $imagen = $_FILES['imagen']['name'];
        $tmp = $_FILES['imagen']['tmp_name'];
        move_uploaded_file($tmp,"../uploads/".$imagen);

        $conn->query("UPDATE productos SET nombre='$nombre', descripcion='$descripcion', precio='$precio', 
                      id_categoria='$id_categoria', imagen='$imagen' WHERE id=$id");
    } else {
        $conn->query("UPDATE productos SET nombre='$nombre', descripcion='$descripcion', precio='$precio', 
                      id_categoria='$id_categoria' WHERE id=$id");
    }

    header("Location: agregar_producto.php");
    exit;
}

// OBTENER LISTA DE PRODUCTOS
$productos = $conn->query("SELECT p.*, c.nombre categoria FROM productos p LEFT JOIN categorias c ON p.id_categoria=c.id_categoria");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Productos</title>
    <style>
        body { background:#6b4f3a; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding:20px; margin:0; }
        
        /* Contenedor Principal */
        .box { 
            background:white; 
            padding:30px; 
            border-radius:15px; 
            max-width: 1100px; 
            margin: 20px auto; 
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            overflow-x: auto; 
        }

        /* Estilo para el encabezado con el botón de cerrar sesión */
        .header-admin {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #eee;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        h2 { color: #4b3621; margin: 0; border: none; }

        .btn-logout {
            text-decoration: none;
            background: #f3f4f6;
            color: #ef4444;
            padding: 8px 15px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            transition: 0.3s;
            border: 1px solid #fee2e2;
        }
        .btn-logout:hover { background: #fee2e2; }

        /* Estilos del Formulario */
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input[type="text"], input[type="number"], textarea, select { 
            width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; font-size: 14px;
        }
        input[type="file"] { padding: 10px 0; }
        
        .btn-main { 
            background:#b08968; color:white; border:none; padding:12px 25px; 
            cursor:pointer; border-radius:8px; font-weight:bold; width: 100%; font-size: 16px; transition: 0.3s;
        }
        .btn-main:hover { background:#7f5539; }

        /* Estilos de la Tabla */
        table { width:100%; border-collapse:collapse; margin-top:30px; min-width: 900px; }
        th { background:#fdfaf7; color:#6b4f3a; padding:15px; text-align:center; border-bottom: 2px solid #b08968; }
        td { padding:15px; border-bottom:1px solid #eee; text-align:center; vertical-align: middle; }

        /* Estados (Labels) */
        .badge { padding: 5px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; }
        .disp { background: #dcfce7; color: #166534; }
        .nodisp { background: #fee2e2; color: #991b1b; }

        /* Acciones en Tabla */
        .form-inline { display: flex; gap: 5px; justify-content: center; align-items: center; }
        .form-inline select { width: auto; padding: 5px; margin: 0; font-size: 12px; }
        .btn-ok { background: #b08968; padding: 5px 10px; color: white; border: none; border-radius: 4px; cursor: pointer; }
        
        .btn-action { 
            text-decoration: none; padding: 8px 12px; border-radius: 6px; color: white; font-size: 14px; display: inline-block; transition: 0.2s;
        }
        .btn-edit { background: #3b82f6; }
        .btn-edit:hover { background: #2563eb; }
        .btn-delete { background: #ef4444; }
        .btn-delete:hover { background: #dc2626; }

        .img-prod { border-radius: 8px; object-fit: cover; border: 1px solid #eee; }
    </style>
</head>
<body>

<div class="box">
    <div class="header-admin">
        <h2><?php echo $editar ? "✏️ Editar Producto" : "➕ Agregar Nuevo Producto"; ?></h2>
        <a href="logout.php" class="btn-logout">Cerrar Sesión 🚪</a>
    </div>
    
    <form method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $editar['id'] ?? ''; ?>">
        
        <div class="form-group">
            <label>Nombre del Producto</label>
            <input type="text" name="nombre" value="<?php echo $editar['nombre'] ?? ''; ?>" placeholder="Ej: Pulsera de Ojo Turco" required>
        </div>

        <div class="form-group">
            <label>Descripción</label>
            <textarea name="descripcion" rows="3" placeholder="Detalles del producto..."><?php echo $editar['descripcion'] ?? ''; ?></textarea>
        </div>

        <div style="display: flex; gap: 15px;">
            <div class="form-group" style="flex: 1;">
                <label>Precio ($)</label>
                <input type="number" step="0.01" name="precio" value="<?php echo $editar['precio'] ?? ''; ?>" required>
            </div>
            <div class="form-group" style="flex: 1;">
                <label>Categoría</label>
                <select name="id_categoria">
                    <?php
                    $cat = $conn->query("SELECT * FROM categorias");
                    while($c=$cat->fetch_assoc()){
                        $sel = ($editar && $editar['id_categoria']==$c['id_categoria']) ? "selected":"";
                        echo "<option value='{$c['id_categoria']}' $sel>{$c['nombre']}</option>";
                    }
                    ?>
                </select>
            </div>
        </div>

        <?php if(!$editar): ?>
        <div class="form-group">
            <label>Estado Inicial</label>
            <select name="disponible">
                <option value="1">Disponible</option>
                <option value="0">No disponible</option>
            </select>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label>Imagen del Producto</label>
            <input type="file" name="imagen">
        </div>

        <button type="submit" name="<?php echo $editar ? 'actualizar' : 'guardar'; ?>" class="btn-main">
            <?php echo $editar ? 'Guardar Cambios' : 'Registrar Producto'; ?>
        </button>
        
        <?php if($editar): ?>
            <div style="text-align: center; margin-top: 10px;">
                <a href="agregar_producto.php" style="color: #666;">Cancelar edición</a>
            </div>
        <?php endif; ?>
    </form>

    <br><hr><br>

    <h2>📦 Inventario de Productos</h2>

    <table>
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Producto</th>
                <th>Categoría</th>
                <th>Precio</th>
                <th>Estado</th>
                <th>Cambiar Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if($productos) while($p=$productos->fetch_assoc()): ?>
            <tr>
                <td>
                    <?php if($p['imagen']): ?>
                        <img src="../uploads/<?php echo $p['imagen']; ?>" width="60" height="60" class="img-prod">
                    <?php else: ?>
                        <div style="width:60px; height:60px; background:#f0f0f0; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:10px; color:#999;">Sin foto</div>
                    <?php endif; ?>
                </td>
                <td style="text-align: left;">
                    <strong><?php echo $p['nombre']; ?></strong><br>
                    <small style="color: #888;"><?php echo substr($p['descripcion'], 0, 40) . '...'; ?></small>
                </td>
                <td><?php echo $p['categoria'] ?? 'Sin categoría'; ?></td>
                <td><strong>$<?php echo number_format($p['precio'], 2); ?></strong></td>
                <td>
                    <span class="badge <?php echo intval($p['stock']) > 0 ? 'disp' : 'nodisp'; ?>">
                        <?php echo intval($p['stock']) > 0 ? 'Disponible' : 'Agotado'; ?>
                    </span>
                </td>
                <td>
                    <form method="POST" class="form-inline">
                        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
                        <select name="estado">
                            <option value="1" <?php echo $p['disponible'] == 1 ? 'selected' : ''; ?>>Disponible</option>
                            <option value="0" <?php echo $p['disponible'] == 0 ? 'selected' : ''; ?>>No disponible</option>
                        </select>
                        <button type="submit" name="cambiar_estado" class="btn-ok">OK</button>
                    </form>
                </td>
                <td>
                    <a href="?editar=<?php echo $p['id']; ?>" class="btn-action btn-edit" title="Editar">✏️</a>
                    <a href="?eliminar=<?php echo $p['id']; ?>" 
                       class="btn-action btn-delete" 
                       onclick="return confirm('¿Seguro que quieres borrar este producto?')" title="Eliminar">🗑️</a>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>