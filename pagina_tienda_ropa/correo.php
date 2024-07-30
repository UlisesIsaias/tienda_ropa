<?php
session_start();
include 'php/db.php';

function updateStock($conn, $cart) {
    foreach ($cart as $item) {
        $producto_id = $item['id'];
        $talla = $item['size'];
        $cantidad = $item['quantity'];

        // Consultar la cantidad actual en la base de datos
        $sql = "SELECT cantidad FROM stock WHERE producto_id = ? AND talla = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('is', $producto_id, $talla);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $cantidad_actual = $row['cantidad'];

            // Calcular la nueva cantidad
            $nueva_cantidad = $cantidad_actual - $cantidad;

            // Actualizar la base de datos
            $sql_update = "UPDATE stock SET cantidad = ? WHERE producto_id = ? AND talla = ?";
            $stmt_update = $conn->prepare($sql_update);
            $stmt_update->bind_param('iis', $nueva_cantidad, $producto_id, $talla);
            $stmt_update->execute();
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recuperar datos del formulario del carrito
    $cart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
    $total_price = 0;
    $total_quantity = 0;

    // Calcular el total del carrito
    foreach ($cart as $item) {
        $total_price += $item['price'] * $item['quantity'];
        $total_quantity += $item['quantity'];
    }

    // Datos del formulario de correo
    $email = $_POST['email'];

    // Generar el contenido del ticket
    $ticket_content = generateTicket($cart, $total_price, $total_quantity);

    // Envío del correo electrónico con el ticket adjunto o contenido del ticket
    $subject = "Compra en nuestra tienda";
    $headers = "From: tuemail@tuempresa.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

    // Envío del correo electrónico
    $mailSent = mail($email, $subject, $ticket_content, $headers);

    if ($mailSent) {
        // Actualizar el stock en la base de datos
        updateStock($conn, $cart);

        // Si se envió correctamente el correo
        echo "<h2>¡Compra realizada con éxito!</h2>";
        echo "<p>Se ha enviado un ticket de compra a <strong>$email</strong>.</p>";
        echo "<a href='index.php'>Volver a la página principal</a>";
    } else {
        // Si hubo un error al enviar el correo
        echo "<h2>¡Error!</h2>";
        echo "<p>No se pudo enviar el correo electrónico.</p>";
        echo "<a href='index.php'>Volver a la página principal</a>";
    }
}

function generateTicket($cart, $total_price, $total_quantity) {
    $ticket_content = "<html><body>";
    $ticket_content .= "<h1>Ticket de Compra</h1>";
    $ticket_content .= "<p>Total de Productos: $total_quantity</p>";
    $ticket_content .= "<p>Total Precio: $" . number_format($total_price, 2) . "</p>";
    
    foreach ($cart as $item) {
        $ticket_content .= "<p>{$item['name']} - {$item['color']} - {$item['size']} - Cantidad: {$item['quantity']} - Precio Unitario: $" . number_format($item['price'], 2) . "</p>";
    }

    $ticket_content .= "</body></html>";

    return $ticket_content;
}
?>