<?php 
include('conexao.php'); 

// 1. Buscar os dados do carro pelo ID recebido via URL
if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $res = $conn->query("SELECT * FROM Carro WHERE id_carro = $id");
    $carro = $res->fetch_assoc();

    if (!$carro) {
        die("Veículo não encontrado.");
    }
}

// 2. Processar a atualização quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_carro = $_POST['id_carro'];
    $id_modelo = $_POST['id_modelo'];
    $ano = $_POST['ano'];
    $preco = $_POST['preco'];
    $id_loja = $_POST['id_loja'];
    
    // Pega o nome da foto que já está no banco
    $foto_nome = $_POST['foto_atual']; 

    // Lógica para Nova Foto
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extensao, $permitidas)) {
            $novo_nome = md5(uniqid()) . "." . $extensao;
            $diretorio = "uploads/";

            if (move_uploaded_file($_FILES['foto']['tmp_name'], $diretorio . $novo_nome)) {
                // Se a foto antiga não for a padrão, apaga ela para economizar espaço
                if ($foto_nome != 'sem-foto.jpg' && file_exists($diretorio . $foto_nome)) {
                    unlink($diretorio . $foto_nome);
                }
                $foto_nome = $novo_nome; // O banco agora receberá o novo nome
            }
        }
    }

    $sql = "UPDATE Carro SET 
            id_modelo = '$id_modelo', 
            ano = '$ano', 
            preco = '$preco', 
            id_loja = '$id_loja',
            foto = '$foto_nome' 
            WHERE id_carro = $id_carro";
    
    if ($conn->query($sql)) {
        header("Location: index.php?msg=editado");
        exit();
    } else {
        $erro = "Erro ao atualizar: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Editar Veículo - AutoManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .preview-img { width: 100%; max-height: 200px; object-fit: cover; border-radius: 10px; margin-bottom: 15px; border: 1px solid #ddd; }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow border-0 mx-auto" style="max-width: 600px;">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Editar Veículo #<?= $carro['id_carro'] ?></h5>
            </div>
            <div class="card-body">
                <?php if(isset($erro)) echo "<div class='alert alert-danger'>$erro</div>"; ?>

                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_carro" value="<?= $carro['id_carro'] ?>">
                    <input type="hidden" name="foto_atual" value="<?= $carro['foto'] ?>">
                    
                    <div class="row">
                        <div class="col-md-5">
                            <label class="form-label fw-bold small text-muted">Foto Atual</label>
                            <img src="uploads/<?= $carro['foto'] ?? 'sem-foto.jpg' ?>" class="preview-img" alt="Foto">
                        </div>
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Alterar Imagem</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                            </div>
                            
                            <div class="mb-3">
                                <label class="form-label fw-bold">ID da Loja</label>
                                <input type="number" name="id_loja" class="form-control" value="<?= $carro['id_loja'] ?>" required>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Marca e Modelo</label>
                        <select name="id_modelo" class="form-select" required>
                            <?php
                            $modelos = $conn->query("SELECT mo.id_modelo, mo.nome_modelo, ma.nome_marca 
                                                   FROM Modelo mo 
                                                   JOIN Marca ma ON mo.id_marca = ma.id_marca 
                                                   ORDER BY ma.nome_marca ASC");
                            
                            while($m = $modelos->fetch_assoc()) {
                                $selected = ($m['id_modelo'] == $carro['id_modelo']) ? "selected" : "";
                                echo "<option value='{$m['id_modelo']}' $selected>{$m['nome_marca']} - {$m['nome_modelo']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Ano</label>
                            <input type="text" name="ano" class="form-control" value="<?= $carro['ano'] ?>" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Preço (R$)</label>
                            <input type="number" step="0.01" name="preco" class="form-control" value="<?= $carro['preco'] ?>" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                        <a href="index.php" class="btn btn-outline-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>