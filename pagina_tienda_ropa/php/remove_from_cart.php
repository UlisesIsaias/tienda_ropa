<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = $_POST['product_id'];
    $product_size = $_POST['product_size'];

    if (isset($_SESSION['cart'])) {
        $cart = $_SESSION['cart'];

        // Buscar el producto en el carrito y eliminarlo
        foreach ($cart as $key => $item) {
            if ($item['id'] == $product_id && $item['size'] == $product_size) {
                unset($cart[$key]);
                break;
            }
        }

        // Reindexar el array
        $_SESSION['cart'] = array_values($cart);
    }
}

header('Location: ../carrito.php');
exit();
?>
