<?php
session_start();

// Verificar si el usuario ha iniciado sesión y es administrador
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

include 'php/db.php';

// Manejo de la acción del formulario (agregar stock)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['actualizar'])) {
    $producto_id = $_POST['producto_id'];
    $talla = $_POST['talla'];
    $cantidad = $_POST['cantidad'];

    // Obtener el límite de stock para el producto y talla específicos
    $sql_limite = "SELECT limite_stock FROM stock WHERE producto_id = $producto_id AND talla = '$talla'";
    $result_limite = $conn->query($sql_limite);

    if ($result_limite->num_rows > 0) {
        $row_limite = $result_limite->fetch_assoc();
        $limite_stock = $row_limite['limite_stock'];
    } else {
        // Si no hay límite establecido, puedes establecer un valor predeterminado
        $limite_stock = 100; // Cambia esto según tus necesidades
    }

    // Obtener el stock actual para el producto y talla específicos
    $sql_actual = "SELECT cantidad FROM stock WHERE producto_id = $producto_id AND talla = '$talla'";
    $result_actual = $conn->query($sql_actual);

    if ($result_actual->num_rows > 0) {
        $row_actual = $result_actual->fetch_assoc();
        $cantidad_actual = $row_actual['cantidad'];
    } else {
        $cantidad_actual = 0;
    }

    $nueva_cantidad = $cantidad_actual + $cantidad;

    if ($nueva_cantidad > $limite_stock) {
        $error = "No se puede agregar stock. La cantidad total excede el límite de $limite_stock.";
    } else {
        // Actualización del stock (INSERT INTO si no existe, UPDATE si existe)
        $sql = "INSERT INTO stock (producto_id, talla, cantidad, limite_stock) VALUES ($producto_id, '$talla', $nueva_cantidad, $limite_stock)
                ON DUPLICATE KEY UPDATE cantidad = $nueva_cantidad";
        
        if ($conn->query($sql) === TRUE) {
            $success = "Stock actualizado correctamente.";
        } else {
            $error = "Error al actualizar el stock: " . $conn->error;
        }
    }
}

// Obtener la lista de productos para mostrar en el formulario
$sql_productos = "SELECT id, nombre FROM productos";
$result_productos = $conn->query($sql_productos);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Stock</title>
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f0f0f0;
            margin: 0;
            padding: 0;
        }

        .admin-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
        }

        .input-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
            color: #555;
            font-weight: bold;
        }

        select, input[type="number"] {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .button-container {
            text-align: center;
            margin-top: 20px;
        }

        .button-container form {
            display: inline;
        }

        button[type="submit"], button[type="button"] {
            background-color: #4CAF50;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin: 10px;
            transition: background-color 0.3s ease;
        }

        button[type="submit"]:hover, button[type="button"]:hover {
            background-color: #45a049;
        }

        .error-message {
            color: #f44336;
            margin-bottom: 10px;
            text-align: center;
        }

        .success-message {
            color: #4CAF50;
            margin-bottom: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <h2>Administrar Stock</h2>
        <?php
        if (isset($error)) {
            echo '<p class="error-message">' . $error . '</p>';
        } elseif (isset($success)) {
            echo '<p class="success-message">' . $success . '</p>';
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="input-group">
                <label for="producto_id">Producto:</label>
                <select name="producto_id" id="producto_id" required>
                    <option value="">Selecciona un producto</option>
                    <?php
                    if ($result_productos->num_rows > 0) {
                        while ($row = $result_productos->fetch_assoc()) {
                            echo '<option value="' . $row['id'] . '">' . $row['nombre'] . '</option>';
                        }
                    }
                    ?>
                </select>
            </div>
            <div class="input-group">
                <label for="talla">Talla:</label>
                <select name="talla" id="talla" required>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                </select>
            </div>
            <div class="input-group">
                <label for="cantidad">Cantidad:</label>
                <input type="number" id="cantidad" name="cantidad" min="0" required>
            </div>
            <div class="button-container">
                <button type="submit" name="actualizar">Actualizar Stock</button>
            </div>
        </form>
        <div class="button-container">
            <form action="index.php" method="get">
                <button type="submit" name="volver">Volver a la página principal</button>
            </form>
        </div>
    </div>
</body>
</html>

<?php
$conn->close();
?>
