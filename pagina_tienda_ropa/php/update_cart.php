<?php
session_start();
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $product_size = $_POST['product_size'];
    $new_quantity = $_POST['quantity'];

    // Verificar el stock disponible
    $sql = "SELECT cantidad FROM stock WHERE producto_id = ? AND talla = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $product_id, $product_size);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $available_stock = $row ? $row['cantidad'] : 0;

    if ($available_stock >= $new_quantity) {
        $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();

        // Actualizar la cantidad en el carrito
        foreach ($cart as &$item) {
            if ($item['id'] == $product_id && $item['size'] == $product_size) {
                $item['quantity'] = $new_quantity;
                break;
            }
        }

        $_SESSION['cart'] = $cart;
        header('Location: ../carrito.php');
        exit();
    } else {
        echo "No hay suficiente stock disponible para el producto solicitado.";
    }
}
?>
