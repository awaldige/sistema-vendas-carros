<?php 
include('conexao.php'); 

if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit();
}

$id = (int)$_GET['id']; 

// Buscar dados do carro (incluindo marca e modelo para salvar na venda)
$res = $conn->query("SELECT c.*, mo.nome_modelo, ma.nome_marca 
                     FROM Carro c 
                     JOIN Modelo mo ON c.id_modelo = mo.id_modelo 
                     JOIN Marca ma ON mo.id_marca = ma.id_marca 
                     WHERE c.id_carro = $id");
$carro = $res->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id_carro = $_POST['id_carro'];
    $valor_venda = $_POST['valor_venda'];
    $data_venda = date('Y-m-d');
    
    // Capturamos os nomes antes de deletar o carro
    $nome_modelo = $conn->real_escape_string($carro['nome_modelo']);
    $nome_marca = $conn->real_escape_string($carro['nome_marca']);

    // 1. Registramos a venda SALVANDO o nome e a marca
    $sql_venda = "INSERT INTO Venda (id_carro, valor_total, data_venda, veiculo_nome, veiculo_marca) 
                  VALUES ('$id_carro', '$valor_venda', '$data_venda', '$nome_modelo', '$nome_marca')";
    
    if ($conn->query($sql_venda)) {
        
        // 2. Agora removemos do estoque
        $sql_delete = "DELETE FROM Carro WHERE id_carro = $id_carro";
        
        if ($conn->query($sql_delete)) {
            header("Location: index.php?msg=vendido");
            exit();
        } else {
            echo "Erro ao remover do estoque: " . $conn->error;
        }

    } else {
        echo "Erro ao registrar venda: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Confirmar Venda - AutoManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow border-0 mx-auto" style="max-width: 450px; border-radius: 15px;">
            <div class="card-header bg-success text-white py-3 text-center">
                <h5 class="mb-0">💰 Confirmar Venda</h5>
            </div>
            <div class="card-body p-4 text-center">
                <h2 class="fw-bold mb-0"><?= $carro['nome_marca'] ?></h2>
                <h4 class="text-secondary mb-3"><?= $carro['nome_modelo'] ?></h4>
                <div class="badge bg-light text-dark border mb-4">Ano: <?= $carro['ano'] ?></div>
                
                <form method="POST">
                    <input type="hidden" name="id_carro" value="<?= $carro['id_carro'] ?>">
                    
                    <div class="mb-4 text-start">
                        <label class="form-label fw-bold small text-muted">VALOR FINAL DA VENDA</label>
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white">R$</span>
                            <input type="number" step="0.01" name="valor_venda" 
                                   class="form-control fw-bold text-success" 
                                   value="<?= $carro['preco'] ?>" required>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg shadow-sm">Confirmar Venda</button>
                        <a href="index.php" class="btn btn-link text-secondary">Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>