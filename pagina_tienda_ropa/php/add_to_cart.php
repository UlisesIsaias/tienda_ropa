<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_color = $_POST['product_color'];
    $product_size = $_POST['product_size'];
    $product_quantity = $_POST['product_quantity'];
    $product_image = $_POST['product_image'];

    // Verificar el stock disponible
    $sql = "SELECT cantidad FROM stock WHERE producto_id = ? AND talla = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $product_id, $product_size);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $available_stock = $row ? $row['cantidad'] : 0;

    if ($available_stock >= $product_quantity) {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

        $found = false;
        // Buscar si el producto ya está en el carrito
        foreach ($cart as &$item) {
            if ($item['id'] == $product_id && $item['size'] == $product_size) {
                $item['quantity'] += $product_quantity;
                $found = true;
                break;
            }
        }
        if (!$found) {
            $cart[] = array(
                'id' => $product_id,
                'name' => $product_name,
                'price' => $product_price,
                'color' => $product_color,
                'size' => $product_size,
                'quantity' => $product_quantity,
                'image' => $product_image
            );
        }

        $_SESSION['cart'] = $cart;
        header('Location: ../carrito.php');
        exit();
    } else {
        echo "No hay suficiente stock disponible para el producto solicitado.";
    }
}
?>
