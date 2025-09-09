<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Agregar Producto</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="style.css" />
  <link rel="icon" type="image/png" sizes="32x32" href="../img/ico/favico_admin.png">
</head>
<body>
    <div class="form-container">
        <a href="panel.php" class="volver">
          <i class="fas fa-arrow-left"></i> Volver al Panel
        </a>
        
        <h2>Agregar Producto</h2>
        <form action="procesar_agregar_producto.php" method="POST">
        
        <label for="codigo">Código:</label>
        <input type="text" name="codigo" id="codigo" required>

        <label for="nombre">Nombre del producto:</label>
        <input type="text" name="nombre" id="nombre" required>

        <label for="descripcion">Descripción:</label>
        <textarea name="descripcion" id="descripcion" rows="3" required></textarea>

        <label for="precio">Precio:</label>
        <input type="number" step="0.01" name="precio" id="precio" required>

        <label for="imagen">Ruta de la imagen:</label>
        <input type="text" name="imagen" id="imagen" placeholder="ej: img/buso1.jpg" required>

        <label for="categoria_id">Categoría:</label>
        <select name="categoria_id" id="categoria_id" required>
            <option value="">-- Selecciona una categoría --</option>
            <option value="1">Damas</option>
            <option value="2">Caballeros</option>
        </select>

        <label for="subcategoria_id">Subcategoría:</label>
        <select name="subcategoria_id" id="subcategoria_id" disabled required>
            <option value="">-- Selecciona primero una categoría --</option>
        </select>

        <button type="submit"><i class="fas fa-save"></i> Agregar</button>
    </form>
    </div>

  <script>
    const categoriaSelect = document.getElementById("categoria_id");
    const subcategoriaSelect = document.getElementById("subcategoria_id");

    const opciones = {
      1: [ // Damas
        { value: 1, text: "Busos" },
        { value: 2, text: "Sudaderas" },
        { value: 4, text: "Faldas" }
      ],
      2: [ // Caballeros
        { value: 1, text: "Busos" },
        { value: 2, text: "Sudaderas" },
        { value: 3, text: "Pantalonetas" }
      ]
    };

    categoriaSelect.addEventListener("change", () => {
      subcategoriaSelect.innerHTML = "";
      if (categoriaSelect.value) {
        subcategoriaSelect.disabled = false;
        opciones[categoriaSelect.value].forEach(opt => {
          const option = document.createElement("option");
          option.value = opt.value;
          option.textContent = opt.text;
          subcategoriaSelect.appendChild(option);
        });
      } else {
        subcategoriaSelect.disabled = true;
        const option = document.createElement("option");
        option.textContent = "-- Selecciona primero una categoría --";
        option.value = "";
        subcategoriaSelect.appendChild(option);
      }
    });
  </script>
</body>
</html>
