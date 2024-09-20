<?php
session_start();
include_once('connection.php');

if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $preco = floatval($_POST['preco']);
    $background_color = $_POST['background_color'];  // Get the color

    // Insert product first
    $stmt = $conn->prepare("INSERT INTO produtos (titulo, descricao, preco) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $titulo, $descricao, $preco);

    if ($stmt->execute()) {
        $product_id = $stmt->insert_id;
        $stmt->close();

        // Handle image upload
        if (!empty($_FILES['imagem']['name'])) {
            $image_name = strtolower(str_replace(' ', '_', $titulo)) . "_{$product_id}." . pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $target_dir = "../images/";
            $target_file = $target_dir . basename($image_name);

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $target_file)) {
                // Save image info to the database
                $stmt = $conn->prepare("INSERT INTO images (product_id, image_path, background_color) VALUES (?, ?, ?)");
                $stmt->bind_param("iss", $product_id, $image_name, $background_color);
                $stmt->execute();
                $stmt->close();

                $success_message = "Produto e imagem adicionados com sucesso!";
            } else {
                $error_message = "Erro ao fazer o upload da imagem.";
            }
        }
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

        .add-lancheira-container input,
        .add-lancheira-container textarea {
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

            <button id="addImageBtn">Adicionar Imagem</button>

            <!-- Modal for Image and Color Picker -->
            <div id="imageModal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Pré-visualizar Imagem</h2>

                    <div id="carouselPreview">
                        <!-- Your carousel code goes here -->
                    </div>

                    <label for="colorPicker">Escolha a cor de fundo</label>
                    <input type="color" id="colorPicker" name="background_color" value="#ffffff">

                    <input type="file" id="imageUpload" name="imagem" accept="image/*" required>
                </div>
            </div>
        </form>
        <script>
            // Modal handling
            var modal = document.getElementById("imageModal");
            var btn = document.getElementById("addImageBtn");
            var span = document.getElementsByClassName("close")[0];

            btn.onclick = function() {
                modal.style.display = "block";
            }

            span.onclick = function() {
                modal.style.display = "none";
            }

            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }
        </script>
    </main>

    <footer>
        <p>&copy; 2024 Minha Loja. Todos os direitos reservados.</p>
    </footer>
</body>

</html>