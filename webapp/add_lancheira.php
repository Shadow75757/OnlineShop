<?php
session_start();
include_once('connection.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle form submission to add a new product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $_POST['titulo'];
    $descricao = $_POST['descricao'];
    $preco = $_POST['preco'];
    $imagem = $_FILES['imagem']['name'];

    // Handle image upload
    $target_dir = "../images/";
    $target_file = $target_dir . basename($_FILES['imagem']['name']);
    move_uploaded_file($_FILES['imagem']['tmp_name'], $target_file);

    // Insert product into the database
    $sql = "INSERT INTO produtos (titulo, descricao, preco, imagem) VALUES ('$titulo', '$descricao', '$preco', '$imagem')";
    
    if ($conn->query($sql) === TRUE) {
        $success_message = "Produto adicionado com sucesso!";
    } else {
        $error_message = "Erro ao adicionar o produto: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicionar Lancheira</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="logo">
            <img src="../images/logo.png" alt="Logo da Empresa">
            <span>Minha Loja - Admin</span>
        </div>
        <div class="header-buttons">
            <a href="../index.php" class="btn">Voltar à Loja</a>
        </div>
    </header>
<style>
    .add-lancheira-container {
    width: 400px;
    margin: 120px auto;
    padding: 20px;
    background-color: #fff;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}

.add-lancheira-container h2 {
    text-align: center;
    margin-bottom: 20px;
    color: #333;
}

.add-lancheira-container form {
    display: flex;
    flex-direction: column;
}

.add-lancheira-container label {
    margin-bottom: 5px;
    color: #333;
}

.add-lancheira-container input, .add-lancheira-container textarea {
    padding: 10px;
    margin-bottom: 15px;
    border: 1px solid #ccc;
    border-radius: 5px;
    width: 100%;
}

.add-lancheira-container input[type="file"] {
    padding: 3px;
}

.add-lancheira-container button {
    padding: 10px;
    background-color: #333;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    text-align: center;
}

.add-lancheira-container button:hover {
    background-color: #555;
}

.success {
    color: green;
    text-align: center;
    margin-bottom: 15px;
}

.error {
    color: red;
    text-align: center;
    margin-bottom: 15px;
}

</style>
    <main class="add-lancheira-container">
        <h2>Adicionar Nova Lancheira</h2>

        <!-- Show success or error message -->
        <?php if (isset($success_message)) echo '<p class="success">' . $success_message . '</p>'; ?>
        <?php if (isset($error_message)) echo '<p class="error">' . $error_message . '</p>'; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <label for="titulo">Título do Produto</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" required></textarea>

            <label for="preco">Preço (€)</label>
            <input type="number" id="preco" name="preco" step="0.01" required>

            <label for="imagem">Imagem do Produto</label>
            <input type="file" id="imagem" name="imagem" accept="image/*" required>

            <button type="submit">Adicionar Produto</button>
        </form>
    </main>

    <footer>
        <p>&copy; 2024 Minha Loja. Todos os direitos reservados.</p>
    </footer>
</body>
</html>
