<?php 
include('conexao.php'); 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_modelo = (int)$_POST['id_modelo'];
    $ano       = $conn->real_escape_string($_POST['ano']);
    $preco     = $conn->real_escape_string($_POST['preco']);
    $id_loja   = (int)$_POST['id_loja'];
    
    // --- LÓGICA DA FOTO ---
    $foto_nome = "sem-foto.jpg"; // Nome padrão caso não envie foto

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extensao, $extensoes_permitidas)) {
            // Gera um nome único para não sobrescrever fotos com nomes iguais
            $foto_nome = md5(uniqid()) . "." . $extensao;
            $diretorio = "uploads/";

            // Move o arquivo temporário para a pasta definitiva
            move_uploaded_file($_FILES['foto']['tmp_name'], $diretorio . $foto_nome);
        }
    }

    // --- INSERT NO BANCO ---
    $sql = "INSERT INTO Carro (id_modelo, ano, preco, id_loja, foto) 
            VALUES ('$id_modelo', '$ano', '$preco', '$id_loja', '$foto_nome')";

    if ($conn->query($sql)) {
        header("Location: index.php?msg=cadastrado");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastrar Veículo - AutoManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .card { border-radius: 15px; border: none; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h3 class="fw-bold mb-0">✨ Novo Cadastro</h3>
                    <a href="index.php" class="btn btn-outline-secondary btn-sm">Cancelar</a>
                </div>

                <form method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label fw-bold small">LOJA ONDE O CARRO ESTÁ</label>
                        <select name="id_loja" class="form-select form-select-lg" required>
                            <option value="">Selecione a Loja...</option>
                            <?php 
                            $lojas = $conn->query("SELECT * FROM Loja ORDER BY nome");
                            while($l = $lojas->fetch_assoc()) echo "<option value='{$l['id_loja']}'>{$l['nome']}</option>";
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small">MODELO DO VEÍCULO</label>
                        <select name="id_modelo" class="form-select form-select-lg" required>
                            <option value="">Selecione o Modelo...</option>
                            <?php 
                            $modelos = $conn->query("SELECT mo.id_modelo, mo.nome_modelo, ma.nome_marca 
                                                   FROM Modelo mo JOIN Marca ma ON mo.id_marca = ma.id_marca 
                                                   ORDER BY ma.nome_marca, mo.nome_modelo");
                            while($m = $modelos->fetch_assoc()) {
                                echo "<option value='{$m['id_modelo']}'>{$m['nome_marca']} - {$m['nome_modelo']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">ANO</label>
                            <input type="text" name="ano" class="form-control form-control-lg" placeholder="Ex: 2023/2024" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold small">PREÇO (R$)</label>
                            <input type="number" step="0.01" name="preco" class="form-control form-control-lg" placeholder="Ex: 85900" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold small text-primary">FOTO DO VEÍCULO</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white"><i class="bi bi-camera"></i></span>
                            <input type="file" name="foto" class="form-control form-control-lg" accept="image/*">
                        </div>
                        <div class="form-text">Formatos aceitos: JPG, PNG ou WebP.</div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm">
                        <i class="bi bi-check-circle me-2"></i>Finalizar Cadastro
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>