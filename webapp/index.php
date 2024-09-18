<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Conectar ao banco de dados
include('db_connection.php');

// Exemplo de processamento de compra
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $lancheira_id = $_POST['lancheira_id'];
    $preco = $_POST['preco'];
    $opcao_pagamento = $_POST['opcao_pagamento'];

    // Processar pagamento e salvar no banco de dados
    $query = "INSERT INTO compras (user_id, lancheira_id, preco, opcao_pagamento) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("iids", $user_id, $lancheira_id, $preco, $opcao_pagamento);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo "Compra realizada com sucesso!";
    } else {
        echo "Erro ao processar a compra.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Online Shop</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <header>
        <div class="logo">
            <img src="images/generic_logo.png" alt="Logo">
        </div>
        <div class="login-btn">
            <a href="login.php" class="btn">Login</a>
        </div>
    </header>

    <main>
        <section class="carousel">
            <div class="slides">
                <div class="slide">
                    <img src="lancheira1.jpg" alt="Lancheira 1">
                    <h2>Lancheira 1</h2>
                    <p>Preço: 10.00€</p>
                    <button class="buy-btn" onclick="comprar(1)">Comprar</button>
                </div>
                <div class="slide">
                    <img src="lancheira2.jpg" alt="Lancheira 2">
                    <h2>Lancheira 2</h2>
                    <p>Preço: 12.00€</p>
                    <button class="buy-btn" onclick="comprar(2)">Comprar</button>
                </div>
                <!-- Adicione mais slides conforme necessário -->
            </div>
        </section>
    </main>

    <footer>
        <p>Opções de pagamento: MBWay | Multibanco</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
