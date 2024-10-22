<?php
session_start();
include 'php/db.php'; 
$sql = "SELECT id, nombre, precio, color, talla, imagen FROM productos WHERE categoria = 'mujer'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mujer - BANCHS</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap">
    <link rel="stylesheet" href="CSS/mujer.css">
    <link href="https://fonts.googleapis.com/css2?family=Rubik+Mono+One&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <a href="index.php"><img src="src/fg.png" alt="Logo"></a>
            <nav>
                <a href="mujer.php">Mujer</a>
                <a href="hombre.php">Hombre</a>
                <a href="kids.php">Kids</a>
                <a href="carrito.php">Carrito</a>
            </nav>
        </div>
    </header>
    <section class="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="src/BN.png" alt="Imagen 1">
            </div>
            <div class="carousel-item">
                <img src="src/bn2.png" alt="Imagen 2">
            </div>
            <div class="carousel-item">
                <img src="src/bn3.png" alt="Imagen 3">
            </div>
        </div>
        <button class="carousel-control prev" onclick="prevSlide()">&#10094;</button>
        <button class="carousel-control next" onclick="nextSlide()">&#10095;</button>
    </section>
    <section class="products-grid">
        <?php
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $tallas = explode(',', $row["talla"]);
                echo '<div class="product">';
                echo '<img src="' . $row["imagen"] . '" alt="' . $row["nombre"] . '">';
                echo '<h4>' . $row["nombre"] . '</h4>';
                echo '<h4>$' . number_format($row["precio"], 2) . '</h4>';
                echo '<h4>Color: ' . $row["color"] . '</h4>';
                echo '<form method="POST" action="php/add_to_cart.php">';
                echo '<input type="hidden" name="product_id" value="' . $row["id"] . '">';
                echo '<input type="hidden" name="product_name" value="' . $row["nombre"] . '">';
                echo '<input type="hidden" name="product_price" value="' . $row["precio"] . '">';
                echo '<input type="hidden" name="product_color" value="' . $row["color"] . '">';
                echo '<input type="hidden" name="product_image" value="' . $row["imagen"] . '">';
                echo '<label for="talla_' . $row["id"] . '">Talla:</label>';
                echo '<select id="talla_' . $row["id"] . '" name="product_size">';
                foreach ($tallas as $talla) {
                    echo '<option value="' . trim($talla) . '">' . trim($talla) . '</option>';
                }
                echo '</select>';
                echo '<label for="cantidad_' . $row["id"] . '">Cantidad:</label>';
                echo '<input type="number" id="cantidad_' . $row["id"] . '" name="product_quantity" value="1" min="1">';
                echo '<button type="submit">Añadir al carrito</button>';
                echo '</form>';
                echo '</div>';
            }
        } else {
            echo "0 resultados";
        }
        $conn->close();
        ?>
    </section>
    <script>
        let currentIndex = 0;
        const slides = document.querySelectorAll('.carousel-item');
        const totalSlides = slides.length;

        function showSlide(index) {
            if (index >= totalSlides) {
                currentIndex = 0;
            } else if (index < 0) {
                currentIndex = totalSlides - 1;
            } else {
                currentIndex = index;
            }
            const newTransform = -currentIndex * 100 + '%';
            document.querySelector('.carousel-inner').style.transform = `translateX(${newTransform})`;
        }

        function nextSlide() {
            showSlide(currentIndex + 1);
        }

        function prevSlide() {
            showSlide(currentIndex - 1);
        }

        setInterval(nextSlide, 5000);
    </script>
</body>
</html>
