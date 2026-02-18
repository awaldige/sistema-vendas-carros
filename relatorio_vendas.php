<?php 
include('conexao.php'); 

// 1. Lógica de Feedback (caso venha de alguma ação)
$mensagem = "";
if (isset($_GET['msg'])) {
    if ($_GET['msg'] == 'sucesso') $mensagem = "✅ Operação realizada com sucesso!";
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório de Vendas - AutoManager Pro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; font-family: 'Segoe UI', system-ui, -apple-system, sans-serif; }
        .navbar { background: linear-gradient(45deg, #11998e, #38ef7d); border: none; }
        .card-report { border-radius: 15px; border: none; transition: transform 0.2s; }
        .card-report:hover { transform: translateY(-3px); }
        .table-container { background: white; border-radius: 15px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .thead-dark { background-color: #212529; color: white; }
        .btn-pdf { background-color: #ff4757; color: white; border: none; }
        .btn-pdf:hover { background-color: #ff6b81; color: white; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark mb-4 shadow-sm">
        <div class="container">
            <span class="navbar-brand mb-0 h1"><i class="bi bi-graph-up-arrow me-2"></i> Histórico de Vendas</span>
            <a href="index.php" class="btn btn-light btn-sm text-success fw-bold">
                <i class="bi bi-house-door me-1"></i> Dashboard
            </a>
        </div>
    </nav>

    <div class="container">
        
        <?php if ($mensagem): ?>
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <?= $mensagem ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4 g-3">
            <div class="col-md-6">
                <div class="card card-report shadow-sm p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-primary text-white rounded-circle p-3 me-3">
                            <i class="bi bi-cart-check fs-3"></i>
                        </div>
                        <div>
                            <small class="text-muted fw-bold">TOTAL DE VEÍCULOS VENDIDOS</small>
                            <?php 
                            $res = $conn->query("SELECT count(*) as total FROM Venda");
                            $row = $res->fetch_assoc();
                            echo "<h2 class='mb-0 fw-bold text-dark'>".($row['total'] ?? 0)."</h2>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card card-report shadow-sm p-4">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 bg-success text-white rounded-circle p-3 me-3">
                            <i class="bi bi-currency-dollar fs-3"></i>
                        </div>
                        <div>
                            <small class="text-muted fw-bold">FATURAMENTO TOTAL ACUMULADO</small>
                            <?php 
                            $res = $conn->query("SELECT sum(valor_total) as total FROM Venda");
                            $row = $res->fetch_assoc();
                            echo "<h2 class='mb-0 fw-bold text-success'>R$ ".number_format($row['total'] ?? 0, 2, ',', '.')."</h2>";
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="table-container mb-5">
            <div class="p-4 border-bottom bg-white">
                <h5 class="mb-0 fw-bold text-secondary">Listagem Detalhada</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="thead-dark">
                        <tr>
                            <th class="ps-4 py-3">Data</th>
                            <th>Veículo Vendido</th>
                            <th>Loja Origem</th>
                            <th class="text-end">Valor Final</th>
                            <th class="text-center">Documento</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Query otimizada buscando nomes de marcas e lojas
                        $sql = "SELECT v.*, l.nome as loja_nome 
                                FROM Venda v 
                                LEFT JOIN Carro c ON v.id_carro = c.id_carro 
                                LEFT JOIN Loja l ON c.id_loja = l.id_loja 
                                ORDER BY v.data_venda DESC, v.id_venda DESC";
                        
                        $vendas = $conn->query($sql);

                        if ($vendas && $vendas->num_rows > 0):
                            while($v = $vendas->fetch_assoc()): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold"><?= date('d/m/Y', strtotime($v['data_venda'])) ?></div>
                                    <small class="text-muted"><?= date('H:i', strtotime($v['data_venda'])) ?></small>
                                </td>
                                <td>
                                    <?php if(!empty($v['veiculo_marca'])): ?>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($v['veiculo_marca']) ?></div>
                                        <div class="text-secondary small"><?= htmlspecialchars($v['veiculo_nome']) ?></div>
                                    <?php else: ?>
                                        <span class="text-muted italic">Veículo Ref. #<?= $v['id_carro'] ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge rounded-pill bg-light text-dark border px-3">
                                        <?= $v['loja_nome'] ?? 'Direto / Matriz' ?>
                                    </span>
                                </td>
                                <td class="text-end fw-bold text-success">
                                    R$ <?= number_format($v['valor_total'], 2, ',', '.') ?>
                                </td>
                                <td class="text-center">
                                    <a href="imprimir_recibo.php?id=<?= $v['id_venda'] ?>" 
                                       target="_blank" 
                                       class="btn btn-pdf btn-sm px-3 shadow-sm">
                                        <i class="bi bi-file-earmark-pdf me-1"></i> Recibo
                                    </a>
                                </td>
                            </tr>
                            <?php endwhile; 
                        else: ?>
                            <tr>
                                <td colspan="5" class="text-center py-5">
                                    <i class="bi bi-inbox fs-1 text-muted d-block mb-2"></i>
                                    <span class="text-muted">Nenhuma venda encontrada no histórico.</span>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <p class="text-center mt-4 text-muted small">AutoManager Pro v2.0 - Relatórios em tempo real</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>