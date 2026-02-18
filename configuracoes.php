<?php 
include('conexao.php'); 

$mensagem = "";
$tipo_alerta = "success";

// --- LÓGICA DE EXCLUSÃO ---
if (isset($_GET['delete_tipo']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $tipo = $_GET['delete_tipo'];
    $sql = ($tipo == 'marca') ? "DELETE FROM Marca WHERE id_marca = $id" : "DELETE FROM Modelo WHERE id_modelo = $id";

    try {
        if ($conn->query($sql)) {
            header("Location: configuracoes.php?status=excluido");
            exit();
        }
    } catch (Exception $e) {
        header("Location: configuracoes.php?status=erro_fk");
        exit();
    }
}

// --- LÓGICA DE EDIÇÃO (MARCA OU MODELO) ---
if (isset($_POST['salvar_edicao'])) {
    $id = (int)$_POST['id_registro'];
    $nome = $conn->real_escape_string($_POST['nome_novo']);
    $tipo = $_POST['tipo_edicao'];

    if ($tipo == 'marca') {
        $sql = "UPDATE Marca SET nome_marca = '$nome' WHERE id_marca = $id";
    } else {
        $sql = "UPDATE Modelo SET nome_modelo = '$nome' WHERE id_modelo = $id";
    }

    if ($conn->query($sql)) {
        header("Location: configuracoes.php?status=editado");
        exit();
    }
}

// --- LÓGICA DE CADASTRO ---
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_marca'])) {
        $nome = $conn->real_escape_string($_POST['nome_marca']);
        if ($conn->query("INSERT INTO Marca (nome_marca) VALUES ('$nome')")) {
            header("Location: configuracoes.php?status=marca_ok"); exit();
        }
    }
    if (isset($_POST['add_modelo'])) {
        $nome = $conn->real_escape_string($_POST['nome_modelo']);
        $id_marca = (int)$_POST['id_marca'];
        if ($conn->query("INSERT INTO Modelo (nome_modelo, id_marca) VALUES ('$nome', '$id_marca')")) {
            header("Location: configuracoes.php?status=modelo_ok"); exit();
        }
    }   
}

// Mensagens de Feedback
if (isset($_GET['status'])) {
    $msgs = [
        'marca_ok' => "✅ Marca adicionada!",
        'modelo_ok' => "✅ Modelo adicionado!",
        'excluido' => "🗑️ Registro removido com sucesso!",
        'editado' => "✏️ Alteração salva com sucesso!",
        'erro_fk' => "⚠️ Erro: Existem registros dependentes (veículos) ligados a este item."
    ];
    $mensagem = $msgs[$_GET['status']] ?? "";
    if ($_GET['status'] == 'erro_fk') $tipo_alerta = "danger";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Configurações - AutoManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .card { border-radius: 15px; border: none; }
        .scroll-list { max-height: 450px; overflow-y: auto; }
        .btn-action { padding: 4px 8px; font-size: 0.85rem; border-radius: 8px; }
        .table td { vertical-align: middle; }
        .badge-count { font-size: 0.7rem; vertical-align: middle; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">⚙️ Configurações</h2>
            <p class="text-muted">Gerenciamento de Marcas e Modelos</p>
        </div>
        <a href="index.php" class="btn btn-dark shadow-sm px-4">Voltar ao Painel</a>
    </div>

    <?php if ($mensagem): ?>
        <div class="alert alert-<?= $tipo_alerta ?> alert-dismissible fade show shadow-sm" role="alert">
            <?= $mensagem ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4 mb-5">
        <div class="col-md-5">
            <div class="card shadow-sm p-4 h-100 bg-primary text-white">
                <h6 class="fw-bold text-uppercase mb-3 small">Nova Marca</h6>
                <form method="POST">
                    <div class="input-group">
                        <input type="text" name="nome_marca" class="form-control border-0" placeholder="Ex: Toyota" required>
                        <button type="submit" name="add_marca" class="btn btn-light fw-bold">Salvar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="col-md-7">
            <div class="card shadow-sm p-4 h-100 bg-white border-start border-primary border-4">
                <h6 class="fw-bold text-primary text-uppercase mb-3 small">Novo Modelo</h6>
                <form method="POST">
                    <div class="row g-2">
                        <div class="col-md-5">
                            <select name="id_marca" class="form-select" required>
                                <option value="">Selecione a Marca...</option>
                                <?php 
                                $m_list = $conn->query("SELECT * FROM Marca ORDER BY nome_marca");
                                while($m = $m_list->fetch_assoc()) echo "<option value='{$m['id_marca']}'>{$m['nome_marca']}</option>";
                                ?>
                            </select>
                        </div>
                        <div class="col-md-7">
                            <div class="input-group">
                                <input type="text" name="nome_modelo" class="form-control" placeholder="Ex: Corolla" required>
                                <button type="submit" name="add_modelo" class="btn btn-primary px-4">Salvar Modelo</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-5 mb-4">
            <div class="card shadow-sm overflow-hidden">
                <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                    Marcas Registradas
                    <span class="badge bg-secondary badge-count rounded-pill"><?= $conn->query("SELECT id_marca FROM Marca")->num_rows ?></span>
                </div>
                <div class="card-body p-0 scroll-list">
                    <table class="table table-hover mb-0">
                        <tbody>
                            <?php 
                            $marcas = $conn->query("SELECT * FROM Marca ORDER BY nome_marca");
                            while($row = $marcas->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold"><?= $row['nome_marca'] ?></td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-outline-primary btn-action" 
                                                onclick="abrirModal('marca', <?= $row['id_marca'] ?>, '<?= $row['nome_marca'] ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="configuracoes.php?delete_tipo=marca&id=<?= $row['id_marca'] ?>" 
                                           class="btn btn-outline-danger btn-action ms-1" onclick="return confirm('Excluir marca?')">
                                           <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-7 mb-4">
            <div class="card shadow-sm overflow-hidden border-top border-primary border-4">
                <div class="card-header bg-white fw-bold py-3 d-flex justify-content-between align-items-center">
                    Modelos Registrados
                    <span class="badge bg-primary badge-count rounded-pill"><?= $conn->query("SELECT id_modelo FROM Modelo")->num_rows ?></span>
                </div>
                <div class="card-body p-0 scroll-list">
                    <table class="table table-hover mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-4">Marca</th>
                                <th>Modelo</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $modelos = $conn->query("SELECT mo.id_modelo, mo.nome_modelo, ma.nome_marca 
                                                   FROM Modelo mo JOIN Marca ma ON mo.id_marca = ma.id_marca 
                                                   ORDER BY ma.nome_marca, mo.nome_modelo");
                            while($row = $modelos->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 small text-uppercase text-muted"><?= $row['nome_marca'] ?></td>
                                    <td class="fw-semibold"><?= $row['nome_modelo'] ?></td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-outline-primary btn-action" 
                                                onclick="abrirModal('modelo', <?= $row['id_modelo'] ?>, '<?= $row['nome_modelo'] ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="configuracoes.php?delete_tipo=modelo&id=<?= $row['id_modelo'] ?>" 
                                           class="btn btn-outline-danger btn-action ms-1" onclick="return confirm('Excluir modelo?')">
                                           <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEdicao" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="tituloModal">Editar Registro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body py-4">
                    <input type="hidden" name="id_registro" id="edit_id">
                    <input type="hidden" name="tipo_edicao" id="edit_tipo">
                    
                    <label class="form-label fw-bold small text-muted text-uppercase" id="labelInput">Nome</label>
                    <input type="text" name="nome_novo" id="edit_nome" class="form-control form-control-lg" required>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="salvar_edicao" class="btn btn-primary px-4 fw-bold shadow-sm">Confirmar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const modalEdicao = new bootstrap.Modal(document.getElementById('modalEdicao'));
    
    function abrirModal(tipo, id, nome) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_tipo').value = tipo;
        document.getElementById('edit_nome').value = nome;
        
        // Personaliza os textos do modal com base no que está sendo editado
        document.getElementById('tituloModal').innerText = (tipo === 'marca') ? 'Editar Marca' : 'Editar Modelo';
        document.getElementById('labelInput').innerText = (tipo === 'marca') ? 'Novo nome da Marca' : 'Novo nome do Modelo';
        
        modalEdicao.show();
    }
</script>
</body>
</html>