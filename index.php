<?php 
include('conexao.php'); 

// 1. Lógica de Pesquisa
$busca = isset($_GET['busca']) ? trim($conn->real_escape_string($_GET['busca'])) : "";

// 2. Feedbacks de mensagens
$alertas = [
    'excluido'   => "🗑️ Registro removido com sucesso!",
    'cadastrado' => "✅ Cadastro realizado com sucesso!",
    'editado'    => "✏️ Atualização realizada com sucesso!",
    'vendido'    => "🎉 Venda realizada e estoque atualizado!"
];
$mensagem = $alertas[$_GET['msg'] ?? ''] ?? "";

// --- 3. DADOS PARA OS GRÁFICOS ---

// A. Marcas no Estoque (Top 5)
$labels_marcas = [];
$valores_marcas = [];
$sql_m = "SELECT ma.nome_marca, COUNT(c.id_carro) as total 
          FROM Carro c 
          LEFT JOIN Modelo mo ON c.id_modelo = mo.id_modelo 
          LEFT JOIN Marca ma ON mo.id_marca = ma.id_marca 
          GROUP BY ma.id_marca ORDER BY total DESC LIMIT 5";
$res_m = $conn->query($sql_m);
while($m = $res_m->fetch_assoc()){
    $labels_marcas[] = $m['nome_marca'] ?? 'Indefinida';
    $valores_marcas[] = (int)$m['total'];
}

// B. Faturamento Mensal (Últimos 6 meses)
$meses = [];
$vendas_mes = [];
$sql_v = "SELECT DATE_FORMAT(data_venda, '%m/%Y') as mes, SUM(valor_total) as total 
          FROM Venda 
          GROUP BY mes 
          ORDER BY data_venda DESC LIMIT 6";
$res_v = $conn->query($sql_v);
while($v = $res_v->fetch_assoc()){
    $meses[] = $v['mes'];
    $vendas_mes[] = (float)$v['total'];
}
$meses = array_reverse($meses);
$vendas_mes = array_reverse($vendas_mes);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>AutoManager Pro - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f4f7f6; }
        .card-stats { transition: transform 0.2s; border: none; border-radius: 15px; }
        .card-stats:hover { transform: translateY(-5px); }
        .table-container { background: white; border-radius: 15px; overflow: hidden; }
        .btn-action { border-radius: 8px; width: 35px; height: 35px; display: inline-flex; align-items: center; justify-content: center; }
        .navbar { background: linear-gradient(45deg, #212529, #343a40); border: none; }
        .bg-faturamento { background: linear-gradient(45deg, #11998e, #38ef7d); color: white; }
        .img-carro { width: 60px; height: 40px; object-fit: cover; border-radius: 8px; border: 1px solid #ddd; }
        canvas { max-height: 250px; }
    </style>
</head>
<body>

    <nav class="navbar navbar-dark mb-4 shadow-sm bg-dark">
        <div class="container">
            <span class="navbar-brand mb-0 h1">🚗 AutoManager Pro</span>
        </div>
    </nav>

    <div class="container">
        <?php if ($mensagem): ?>
            <div class="alert alert-success alert-dismissible fade show shadow-sm border-0" role="alert">
                <?= $mensagem ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row mb-4 g-3">
            <div class="col-md-3">
                <div class="card card-stats shadow-sm text-center p-3">
                    <h6 class="text-muted fw-bold small">ESTOQUE ATUAL</h6>
                    <?php $row = $conn->query("SELECT count(*) as t FROM Carro")->fetch_assoc(); ?>
                    <h2 class='fw-bold text-primary'><?= $row['t'] ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats shadow-sm text-center p-3">
                    <h6 class="text-muted fw-bold small">CAPITAL EM ESTOQUE</h6>
                    <?php $row = $conn->query("SELECT sum(preco) as t FROM Carro")->fetch_assoc(); ?>
                    <h2 class='fw-bold text-dark'>R$ <?= number_format($row['t'] ?? 0, 0, ',', '.') ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats shadow-sm text-center p-3 bg-faturamento text-white">
                    <h6 class="fw-bold small opacity-75">FATURAMENTO TOTAL</h6>
                    <?php $row = $conn->query("SELECT sum(valor_total) as t FROM Venda")->fetch_assoc(); ?>
                    <h2 class='fw-bold'>R$ <?= number_format($row['t'] ?? 0, 0, ',', '.') ?></h2>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-stats shadow-sm text-center p-3 bg-dark text-white">
                    <h6 class="fw-bold small opacity-75">LOJAS ATIVAS</h6>
                    <?php $row = $conn->query("SELECT count(*) as t FROM Loja")->fetch_assoc(); ?>
                    <h2 class='fw-bold'><?= $row['t'] ?></h2>
                </div>
            </div>
        </div>

        <div class="row mb-4 g-3">
            <div class="col-md-8">
                <div class="card shadow-sm border-0 p-4 h-100" style="border-radius: 15px;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold text-secondary mb-0">CRESCIMENTO DE VENDAS</h6>
                        <i class="bi bi-graph-up-arrow text-success"></i>
                    </div>
                    <canvas id="graficoVendas"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow-sm border-0 p-4 h-100" style="border-radius: 15px;">
                    <h6 class="fw-bold text-secondary mb-3">MIX DE MARCAS</h6>
                    <canvas id="graficoMarcas"></canvas>
                </div>
            </div>
        </div>

        <div class="card shadow-sm border-0 mb-4" style="border-radius: 15px;">
            <div class="card-body p-3">
                <form method="GET" action="index.php" class="row g-2">
                    <div class="col-md-10">
                        <input type="text" name="busca" class="form-control form-control-lg border-0 bg-light" 
                               placeholder="O que você procura? (Marca, Modelo ou Ano)" value="<?= htmlspecialchars($busca) ?>">
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary btn-lg w-100 shadow-sm">Buscar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-container shadow-sm mb-5">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-0">
                <h5 class="mb-0 fw-bold text-secondary">📦 Estoque de Veículos</h5>
                <div>
                    <a href="relatorio_vendas.php" class="btn btn-outline-success btn-sm me-1">📊 Relatório</a>
                    <a href="gerenciar_lojas.php" class="btn btn-outline-dark btn-sm me-1">🏢 Lojas</a>
                    <a href="configuracoes.php" class="btn btn-outline-secondary btn-sm me-1">⚙️ Config</a>
                    <a href="cadastrar.php" class="btn btn-success btn-sm px-3">+ Novo Carro</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Veículo</th>
                            <th>Ano</th>
                            <th>Preço</th>
                            <th>Loja</th>
                            <th class="text-center">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // ADICIONADO c.foto na consulta
                        $sql = "SELECT c.id_carro, c.ano, c.preco, c.foto, mo.nome_modelo, ma.nome_marca, l.nome as loja 
                                FROM Carro c 
                                LEFT JOIN Modelo mo ON c.id_modelo = mo.id_modelo
                                LEFT JOIN Marca ma ON mo.id_marca = ma.id_marca
                                LEFT JOIN Loja l ON c.id_loja = l.id_loja 
                                WHERE ma.nome_marca LIKE '%$busca%' OR mo.nome_modelo LIKE '%$busca%' OR c.ano LIKE '%$busca%'
                                ORDER BY c.id_carro DESC";
                        $result = $conn->query($sql);

                        while($carro = $result->fetch_assoc()): 
                            // Verifica se o arquivo existe, senão usa o padrão
                            $foto_exibicao = (!empty($carro['foto']) && file_exists("uploads/" . $carro['foto'])) ? $carro['foto'] : "sem-foto.jpg";
                        ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center">
                                        <img src="uploads/<?= $foto_exibicao ?>" class="img-carro me-3 shadow-sm" alt="carro">
                                        <div>
                                            <div class="fw-bold text-dark"><?= $carro['nome_marca'] ?></div>
                                            <div class="text-secondary small"><?= $carro['nome_modelo'] ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= $carro['ano'] ?></td>
                                <td class="text-success fw-bold">R$ <?= number_format($carro['preco'], 2, ',', '.') ?></td>
                                <td><span class="badge rounded-pill bg-light text-dark border px-3"><?= $carro['loja'] ?? 'S/ Loja' ?></span></td>
                                <td class="text-center">
                                    <a href="vender.php?id=<?= $carro['id_carro'] ?>" class="btn btn-success btn-sm btn-action">💰</a>
                                    <a href="editar.php?id=<?= $carro['id_carro'] ?>" class="btn btn-outline-primary btn-sm btn-action">✏️</a>
                                    <a href="excluir.php?id=<?= $carro['id_carro'] ?>" class="btn btn-outline-danger btn-sm btn-action" onclick="return confirm('Excluir?')">🗑️</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
    // Gráfico de Vendas
    new Chart(document.getElementById('graficoVendas'), {
        type: 'line',
        data: {
            labels: <?= json_encode($meses) ?>,
            datasets: [{
                label: 'Vendas (R$)',
                data: <?= json_encode($vendas_mes) ?>,
                borderColor: '#11998e',
                backgroundColor: 'rgba(17, 153, 142, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 5
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true }, x: { grid: { display: false } } }
        }
    });

    // Gráfico de Marcas
    new Chart(document.getElementById('graficoMarcas'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode($labels_marcas) ?>,
            datasets: [{
                data: <?= json_encode($valores_marcas) ?>,
                backgroundColor: ['#0d6efd', '#6610f2', '#6f42c1', '#d63384', '#fd7e14'],
                hoverOffset: 10
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } },
            cutout: '70%'
        }
    });
    </script>
</body>
</html>