<?php
session_start();
$cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
$total_price = 0;
$total_quantity = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Carrito de Compras</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="CSS/carrito.css">
    <style>
        body {
            font-family: 'Roboto';
        }
        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            margin: 10px 2px;
            cursor: pointer;
            border: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <h1>Carrito de Compras</h1>
    <table>
        <thead>
            <tr>
                <th>Imagen</th>
                <th>Nombre</th>
                <th>Color</th>
                <th>Talla</th>
                <th>Precio</th>
                <th>Cantidad</th>
                <th>Total</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cart as $item): ?>
                <tr>
                    <td><img src="<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>"></td>
                    <td><?php echo $item['name']; ?></td>
                    <td><?php echo $item['color']; ?></td>
                    <td><?php echo $item['size']; ?></td>
                    <td><?php echo '$' . number_format($item['price'], 2); ?></td>
                    <td>
                        <form method="POST" action="php/update_cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="product_size" value="<?php echo $item['size']; ?>">
                            <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1">
                            <button type="submit">Actualizar</button>
                        </form>
                    </td>
                    <td><?php echo '$' . number_format($item['price'] * $item['quantity'], 2); ?></td>
                    <td>
                        <form method="POST" action="php/remove_from_cart.php">
                            <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                            <input type="hidden" name="product_size" value="<?php echo $item['size']; ?>">
                            <button type="submit">Eliminar</button>
                        </form>
                    </td>
                </tr>
                <?php
                $total_price += $item['price'] * $item['quantity'];
                $total_quantity += $item['quantity'];
                ?>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h2>Total de Productos: <?php echo $total_quantity; ?></h2>
    <h2>Total Precio: <?php echo '$' . number_format($total_price, 2); ?></h2>
    <a href="index.php" class="btn">Seguir Comprando</a>
    <form method="POST" action="correo.php">
    <input type="email" name="email" placeholder="Correo electrónico" required>
    <button type="submit" class="btn">Comprar</button>
</form>
</body>
</html>
