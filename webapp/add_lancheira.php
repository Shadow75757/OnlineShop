<?php
session_start();
include_once('connection.php');

// Check if the user is logged in and is an admin
if (!isset($_SESSION['tipo']) || $_SESSION['tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

// Handle form submission to add a new product and image
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $titulo = $conn->real_escape_string($_POST['titulo']);
    $descricao = $conn->real_escape_string($_POST['descricao']);
    $preco = floatval($_POST['preco']);
    $background_color = $_POST['background_color'];  // Get the color from the color picker

    // Insert product first
    $stmt = $conn->prepare("INSERT INTO produtos (titulo, descricao, preco) VALUES (?, ?, ?)");
    $stmt->bind_param("ssd", $titulo, $descricao, $preco);

    if ($stmt->execute()) {
        $product_id = $stmt->insert_id;  // Get the product ID after insertion
        $stmt->close();

        // Handle image upload
        if (!empty($_FILES['imagem']['name'])) {
            // Create image name based on the product title and product ID
            $image_name = strtolower(str_replace(' ', '_', $titulo)) . "_{$product_id}." . pathinfo($_FILES['imagem']['name'], PATHINFO_EXTENSION);
            $target_dir = "../images/";
            $target_file = $target_dir . basename($image_name);

            if (move_uploaded_file($_FILES['imagem']['tmp_name'], $target_file)) {
                // Save image information into the 'images' table
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <style>
        /* Modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: white;
            margin: 10% auto;
            padding: 20px;
            border-radius: 10px;
            width: 50%;
            text-align: center;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        /* Form container */
        .add-lancheira-container {
            width: 400px;
            margin: auto;
            /* Center horizontally */
            padding: 20px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            box-sizing: border-box;
            position: absolute;
            /* Positioning for vertical centering */
            top: 50%;
            /* Move to the middle of the page */
            left: 50%;
            /* Move to the middle of the page */
            transform: translate(-50%, -50%);
            /* Offset the width and height by half to center */
        }


        .add-lancheira-container h2 {
            text-align: center;
            margin-bottom: 10px;
            /* Gap below title */
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
            /* Gap below inputs */
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 100%;
        }

        .add-lancheira-container button {
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
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

        /* Modal specific styles */
        .input-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            /* Gap between inputs */
        }

        .preview-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: lightgrey;
            padding: 20px;
            margin-bottom: 15px;
            /* Gap below preview */
        }

        .preview-img {
            width: 200px;
            height: auto;
        }

        .thumbnail-preview {
            border: 1px solid grey;
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .modal-footer {
            display: flex;
            justify-content: center;
        }

        /* Hide the number input arrows */
        input[type=number] {
            -moz-appearance: textfield;
            /* Firefox */
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            /* Chrome, Safari, and Opera */
            margin: 0;
            /* Remove default margin */
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">
            <img src="images/generic_logo.png" alt="Logo da Empresa">
            <h1>ADMIN | Shop name</h1>
        </div>
        <div class="header-buttons">
            <a href="../index.php" class="btn">Voltar à Loja</a>
        </div>
    </header>

    <main class="add-lancheira-container">
        <h2>Adicionar Nova Lancheira</h2>

        <!-- Show success or error message -->
        <?php if (isset($success_message)) echo '<p class="success">' . $success_message . '</p>'; ?>
        <?php if (isset($error_message)) echo '<p class="error">' . $error_message . '</p>'; ?>

        <form action="" method="POST" enctype="multipart/form-data">
            <label for="titulo">Título do Produto</label>
            <input type="text" id="titulo" name="titulo" required>

            <label for="descricao">Descrição</label>
            <textarea id="descricao" name="descricao" style="resize:none"></textarea>

            <label for="preco">Preço (€)</label>
            <input type="number" id="preco" name="preco" step="0.01" required>

            <button type="button" id="addImageBtn" style="width: 100%;">Adicionar Imagem</button>
            <br>
            <button type="submit">Adicionar Produto</button>
        </form>
    </main>

    <div id="imageModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Pré-visualizar Lancheira</h2>
            <br>
            <div class="input-section">
                <div style="width: 50%; padding-right: 10px;">
                    <label for="imageUpload">Escolher Imagem:</label>
                    <input type="file" id="imageUpload" name="imagem" accept="image/*">
                </div>
                <div style="width: 50%;">
                    <label for="backgroundColor">Cor de Fundo:</label>
                    <input type="color" id="backgroundColor" name="backgroundColor" value="#ffffff">
                </div>
            </div>
            <br>
            <div class="preview-section" style="background-color: lightgrey;">
                <div class="left">
                    <span id="previewTitle">Título</span><label> | </label><span id="previewPrice">0.00€</span>
                    <br>
                    <span id="previewDescription">Descrição</span>
                </div>
                <div class="center">
                    <img id="previewImage" src="images/default.png" alt="Preview Image" class="preview-img">
                </div>
                <div class="right">
                    <img id="thumbnailPreview" src="images/default.png" alt="Thumbnail Preview" class="thumbnail-preview">
                </div>
            </div>

            <div class="modal-footer">
                <button id="saveImageBtn">Salvar Imagem</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById("imageModal");
            const btn = document.getElementById("addImageBtn");
            const span = document.getElementsByClassName("close")[0];
            const previewImage = document.getElementById("previewImage");
            const thumbnailPreview = document.getElementById("thumbnailPreview");
            const titleInput = document.getElementById("titulo");
            const descriptionInput = document.getElementById("descricao");
            const priceInput = document.getElementById("preco");
            const backgroundColorInput = document.getElementById("backgroundColor");
            const colorPickerInput = document.getElementById("colorPicker");
            const confirmBtn = document.getElementById("saveImageBtn");

            // Open modal when the button is clicked
            // Open modal when the button is clicked
            btn.onclick = function() {
                if (titleInput.value && descriptionInput.value && priceInput.value) {
                    // Reset previews
                    previewImage.src = 'images/default.png';
                    thumbnailPreview.src = 'images/default.png';
                    document.getElementById('previewTitle').innerText = titleInput.value; // Set title
                    document.getElementById('previewDescription').innerText = descriptionInput.value; // Set description
                    document.getElementById('previewPrice').innerText = `${parseFloat(priceInput.value).toFixed(2)}€`; // Set price
                    modal.style.display = "block";
                } else {
                    // Replace alert with SweetAlert
                    Swal.fire({
                        title: 'Erro!',
                        text: 'Por favor, preencha todos os campos obrigatórios antes de adicionar a imagem.',
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            };


            // Close modal when the user clicks on <span> (x)
            span.onclick = function() {
                modal.style.display = "none";
            };

            // Close modal when the user clicks anywhere outside of the modal
            window.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            };

            // Update the preview when the background color is changed
            backgroundColorInput.addEventListener("input", function() {
                const newColor = this.value; // Get the new color
                document.querySelector(".preview-section").style.backgroundColor = newColor; // Change preview section background
                // Ensure you update the carousel's background
                const carousel = document.querySelector('.carousel-class-selector'); // Replace with your actual carousel selector
                if (carousel) {
                    carousel.style.backgroundColor = newColor; // Change carousel background
                }
            });

            // Image upload handling
            document.getElementById("imageUpload").addEventListener("change", function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        thumbnailPreview.src = e.target.result; // Update thumbnail to the new image
                    };
                    reader.readAsDataURL(file);
                } else {
                    previewImage.src = 'images/default.png';
                    thumbnailPreview.src = 'images/default.png'; // Reset to default if no file is selected
                }
            });

            // Handle confirmation and close modal
            confirmBtn.onclick = function() {
                modal.style.display = "none";
                colorPickerInput.value = backgroundColorInput.value; // Sync values
            };
        });
    </script>
</body>

</html>