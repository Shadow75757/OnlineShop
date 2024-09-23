<?php
session_start();
include_once('connection.php');

// Verificar se o usuário é admin
$isAdmin = isset($_SESSION['tipo']) && $_SESSION['tipo'] === 'admin';
$isLoggedIn = isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
$username = isset($_SESSION['username']) ? $_SESSION['username'] : '';

// Fetch produtos from the database
$query = "SELECT titulo, descricao, imagem, color FROM produtos";
$result = mysqli_query($conn, $query);
$produtos = [];

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $produtos[] = $row; // Store each produto in the produtos array
    }
} else {
    echo "Error fetching products: " . mysqli_error($conn);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online shop</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <div class="logo">
            <img src="images/generic_logo.png" alt="Logo da Empresa">
        </div>
        <nav>
            <a href="#">Demos</a>
            <a href="#">Addons</a>
            <a href="#">Features</a>
            <a href="#">Elements</a>
            <a href="#">Compatibility</a>
        </nav>
        <div class="header-buttons">
            <?php if ($isAdmin): ?>
                <a class="btnfos btnfos-4" href="add_lancheira.php"><span>Adicionar Lancheira</span></a>
            <?php endif; ?>
            <?php if ($isLoggedIn): ?>
                <div class="profile-icon">
                    <span><?= strtoupper($username[0]) ?></span>
                    <div class="profile-dropdown">
                        <a href="profile.php">Profile</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a class="btnfos btnfos-5" href="login.php"><span>Login</span></a>
            <?php endif; ?>
        </div>
    </header>

    <div class="carousel">
        <?php foreach ($produtos as $index => $produto): ?>
            <div class="item <?= $index === 0 ? 'active' : '' ?>">
                <div class="img-box">
                    <img src="images/<?= htmlspecialchars($produto['imagem']) ?>" alt="<?= htmlspecialchars($produto['titulo']) ?>">
                </div>
                <div class="info-box">
                    <div class="info-slider">
                        <div class="info-item" style="--i:<?= $index ?>;">
                            <h2><?= htmlspecialchars($produto['titulo']) ?></h2>
                            <p><?= htmlspecialchars($produto['descricao']) ?></p>
                            <a href="#" class="btn">View More</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <ul class="thumb">
            <?php foreach ($produtos as $produto): ?>
                <li><img src="images/<?= htmlspecialchars($produto['imagem']) ?>" alt="Thumbnail"></li>
            <?php endforeach; ?>
        </ul>
    </div>

    <footer>
        <p>&copy; 2024 - Shop Name | Todos os direitos reservados.</p>
    </footer>

    <script src="script.js"></script>
</body>

</html>