<?php 
include('conexao.php'); 

$mensagem = "";
$tipo_alerta = "success";

// --- LÓGICA PARA ADICIONAR LOJA ---
if (isset($_POST['add_loja'])) {
    $nome = $conn->real_escape_string($_POST['nome_loja']);
    if ($conn->query("INSERT INTO Loja (nome) VALUES ('$nome')")) {
        header("Location: gerenciar_lojas.php?status=cadastrada");
        exit();
    }
}

// --- LÓGICA PARA EDITAR LOJA ---
if (isset($_POST['salvar_edicao'])) {
    $id = (int)$_POST['id_loja'];
    $nome = $conn->real_escape_string($_POST['nome_loja']);
    if ($conn->query("UPDATE Loja SET nome = '$nome' WHERE id_loja = $id")) {
        header("Location: gerenciar_lojas.php?status=editada");
        exit();
    }
}

// --- LÓGICA PARA EXCLUIR LOJA ---
if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    try {
        // O uso do @ suprime warnings se houver restrição de chave estrangeira, 
        // mas o try/catch trata o erro corretamente.
        if ($conn->query("DELETE FROM Loja WHERE id_loja = $id")) {
            header("Location: gerenciar_lojas.php?status=excluida");
            exit();
        }
    } catch (Exception $e) {
        header("Location: gerenciar_lojas.php?status=erro_fk");
        exit();
    }
}

// Mensagens de Feedback
if (isset($_GET['status'])) {
    $msgs = [
        'cadastrada' => "✅ Loja cadastrada com sucesso!",
        'editada'    => "✏️ Loja atualizada com sucesso!",
        'excluida'   => "🗑️ Loja removida com sucesso!",
        'erro_fk'    => "⚠️ Não é possível excluir: existem carros vinculados a esta loja."
    ];
    $mensagem = $msgs[$_GET['status']] ?? "";
    if ($_GET['status'] == 'erro_fk') $tipo_alerta = "danger";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Gestão de Lojas - AutoManager</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .card { border-radius: 15px; border: none; }
        .btn-action { padding: 4px 8px; font-size: 0.85rem; border-radius: 8px; }
        .table td { vertical-align: middle; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">🏢 Gestão de Lojas</h2>
            <p class="text-muted">Administre as unidades e filiais</p>
        </div>
        <a href="index.php" class="btn btn-dark shadow-sm px-4">Voltar ao Painel</a>
    </div>

    <?php if ($mensagem): ?>
        <div class="alert alert-<?= $tipo_alerta ?> alert-dismissible fade show shadow-sm" role="alert">
            <?= $mensagem ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm p-4 text-white bg-success">
                <h6 class="fw-bold text-uppercase mb-3 small">Adicionar Nova Unidade</h6>
                <form method="POST">
                    <div class="mb-3">
                        <input type="text" name="nome_loja" class="form-control border-0" placeholder="Ex: Filial Centro" required>
                    </div>
                    <button type="submit" name="add_loja" class="btn btn-light w-100 fw-bold text-success">Salvar Loja</button>
                </form>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card shadow-sm overflow-hidden">
                <div class="card-header bg-white fw-bold py-3">Lojas Ativas</div>
                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead class="table-light small">
                            <tr>
                                <th class="ps-4 text-start">ID</th>
                                <th class="text-start">Nome da Loja</th>
                                <th class="text-end pe-4">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $res = $conn->query("SELECT * FROM Loja ORDER BY nome ASC");
                            if ($res->num_rows > 0):
                                while($loja = $res->fetch_assoc()): ?>
                                <tr>
                                    <td class="ps-4 text-start text-muted">#<?= $loja['id_loja'] ?></td>
                                    <td class="text-start fw-semibold"><?= $loja['nome'] ?></td>
                                    <td class="text-end pe-4">
                                        <button class="btn btn-outline-primary btn-action" 
                                                onclick="abrirModal(<?= $loja['id_loja'] ?>, '<?= addslashes($loja['nome']) ?>')">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <a href="gerenciar_lojas.php?excluir=<?= $loja['id_loja'] ?>" 
                                           class="btn btn-outline-danger btn-action ms-1" 
                                           onclick="return confirm('Confirmar exclusão desta loja?')">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; 
                            else: ?>
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted">Nenhuma loja cadastrada.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalEditarLoja" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">Editar Unidade</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body py-4">
                    <input type="hidden" name="id_loja" id="edit_id">
                    
                    <label class="form-label fw-bold small text-muted">NOME DA LOJA</label>
                    <input type="text" name="nome_loja" id="edit_nome" class="form-control form-control-lg" required>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" name="salvar_edicao" class="btn btn-success px-4 fw-bold shadow-sm">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Inicializa o modal do Bootstrap
    const modalLoja = new bootstrap.Modal(document.getElementById('modalEditarLoja'));

    // Função para carregar os dados no modal
    function abrirModal(id, nome) {
        document.getElementById('edit_id').value = id;
        document.getElementById('edit_nome').value = nome;
        modalLoja.show();
    }
</script>
</body>
</html>