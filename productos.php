<?php
session_start();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Productos</title>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
    />
    <link rel="stylesheet" href="style_productos.css"/>
    <link rel="icon" type="image/x-icon" href="img/ico/favicon.ico">
</head>
<body>
    <header class="header">
      <h1>RepLicant Apparell</h1>
      <div class="acciones">
        <?php if (isset($_SESSION['usuario_id'])): ?>
          <a><?php echo $_SESSION['usuario_nombre']; ?></a>
          <a href="perfil.php" title=<?php echo $_SESSION['usuario_nombre']; ?>>
            <i class="fas fa-user"></i>
          </a>
          <a href="logout_cliente.php" title="Cerrar sesión">
            <i class="fas fa-right-from-bracket"></i>
          </a>
        <?php else: ?>
          <a href="login.html" title="Iniciar Sesión">
            <i class="fas fa-user"></i>
          </a>
        <?php endif; ?>
        <a href="carrito.php" title="Carrito de Compras">
          <i class="fas fa-shopping-cart"></i>
        </a>
      </div>
    </header>
        <!-- Barra de navegación -->
    <nav class="nav">
      <a href="index.php">Inicio</a>
    </nav>
<?php
try {
    $conexion = new PDO("sqlite:productos.db");
    $conexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Obtener todas las categorías
    $categorias = $conexion->query("SELECT id, nombre FROM categorias")->fetchAll(PDO::FETCH_ASSOC);

    foreach ($categorias as $categoria) {
        echo "<h2 class='categoria'>" . htmlspecialchars($categoria['nombre']) . "</h2>";

        // Buscar subcategorías solo con productos de esta categoría
        $subcategorias = $conexion->prepare("
            SELECT DISTINCT s.id, s.nombre 
            FROM subcategorias s
            JOIN productos p ON p.subcategoria_id = s.id
            WHERE p.categoria_id = ?
            ORDER BY s.nombre
        ");
        $subcategorias->execute([$categoria['id']]);
        $subcats = $subcategorias->fetchAll(PDO::FETCH_ASSOC);

        if (count($subcats) > 0) {
            foreach ($subcats as $sub) {
                echo "<h3 class='subcategoria'>" . htmlspecialchars($sub['nombre']) . "</h3>";

                // Productos de la subcategoría y categoría actual
                $productos = $conexion->prepare("
                    SELECT * 
                    FROM productos 
                    WHERE categoria_id = ? AND subcategoria_id = ?
                ");
                $productos->execute([$categoria['id'], $sub['id']]);
                $resultado = $productos->fetchAll(PDO::FETCH_ASSOC);

                echo "<div class='grid-productos'>";
                if (count($resultado) > 0) {
                    foreach ($resultado as $prod) {
                        echo "
                        <div class='producto'>
                            <img src='" . htmlspecialchars($prod['imagen']) . "' alt='" . htmlspecialchars($prod['nombre']) . "'>
                            <h3>" . htmlspecialchars($prod['nombre']) . "</h3>
                            <p>" . htmlspecialchars($prod['descripcion']) . "</p>
                            <p class='precio'>$" . number_format($prod['precio'], 0, ',', '.') . "</p>
                            <form method='POST' action='carrito.php'>
                              <input type='hidden' name='codigo' value='" . htmlspecialchars($prod['codigo']) . "'>
                              <input type='hidden' name='nombre' value='" . htmlspecialchars($prod['nombre']) . "'>
                              <input type='hidden' name='precio' value='" . htmlspecialchars($prod['precio']) . "'>
                              <input type='hidden' name='imagen' value='" . htmlspecialchars($prod['imagen']) . "'>
                              <button type='submit' name='agregar' class='btn-carrito'>
                                  <i class='fas fa-cart-plus'></i> Añadir al carrito
                              </button>
                            </form>
                        </div>";
                    }
                } else {
                    echo "<p style='color: gray; font-style: italic;'>No hay productos en esta subcategoría.</p>";
                }
                echo "</div>";
            }
        } else {
            echo "<p style='color: gray; font-style: italic;'>No hay subcategorías con productos en esta categoría.</p>";
        }
    }
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
</body>
<!-- Pie de página -->
  <footer>
    <p>&copy; 2025 RepLicant Apparell | Grupo 3 ADSO</p>
      <p>
        <a>Edinson Balaguera</a> |
        <a>Miguel Jimenez</a> |
        <a>Sebastian Sanchez</a>
      </p>
  </footer>
</html>
