<?php
session_start();
include_once('connection.php');

// Verificar se o usuário é admin
$isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Image Carousel</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/generic_logo.png" alt="Logo da Empresa">
            <span>Shop Name</span>
        </div>
        <div class="header-buttons">
            <?php if ($isAdmin): ?>
                <a href="add_lancheira.php" class="btn">Adicionar Lancheira</a>
            <?php endif; ?>
            <a href="login.php" class="btn">Login</a>
        </div>
    </header>
    <div class="carousel">

        <!-- Carousel Items -->
        <div class="item active">
            <div class="img-box">
                <img src="images/image1.jpg" alt="Lancheira Tropical">
            </div>
            <div class="info-box">
                <div class="info-slider">
                    <div class="info-item" style="--i:0;">
                        <h2>Lancheira Tropical</h2>
                        <p>Discover the Tropical Lunchbox, perfect for beach days and outdoor adventures.</p>
                        <a href="#" class="btn">View More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="item">
            <div class="img-box">
                <img src="images/image2.jpg" alt="Lancheira Fitness">
            </div>
            <div class="info-box">
                <div class="info-slider">
                    <div class="info-item" style="--i:1;">
                        <h2>Lancheira Fitness</h2>
                        <p>The Fitness Lunchbox, designed for your active lifestyle with compartments for healthy snacks.</p>
                        <a href="#" class="btn">View More</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="item">
            <div class="img-box">
                <img src="images/image3.jpg" alt="Lancheira Premium">
            </div>
            <div class="info-box">
                <div class="info-slider">
                    <div class="info-item" style="--i:2;">
                        <h2>Lancheira Premium</h2>
                        <p>Experience luxury with the Premium Lunchbox, crafted with high-quality materials.</p>
                        <a href="#" class="btn">View More</a>
                    </div>
                </div>
            </div>
        </div>

        <ul class="thumb">
            <li class="selected"><img src="images/image1.jpg" alt="Thumbnail 1"></li>
            <li><img src="images/image2.jpg" alt="Thumbnail 2"></li>
            <li><img src="images/image3.jpg" alt="Thumbnail 3"></li>
        </ul>

    </div>
    
    <footer>
        <p>&copy; 2024 - Shop Name | Todos os direitos reservados.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
