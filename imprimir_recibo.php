<?php
include('conexao.php');

$id = $_GET['id'] ?? 0;
// Buscamos a venda e tentamos ligar com a loja (ajuste o nome das colunas se necessário)
$sql = "SELECT v.*, l.nome as loja_nome, l.endereco 
        FROM Venda v 
        LEFT JOIN Carro c ON v.id_carro = c.id_carro 
        LEFT JOIN Loja l ON c.id_loja = l.id_loja 
        WHERE v.id_venda = $id";
$res = $conn->query($sql);
$v = $res->fetch_assoc();

if (!$v) die("Venda não encontrada.");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Recibo de Venda #<?= $v['id_venda'] ?></title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; padding: 40px; color: #333; }
        .recibo-box { max-width: 800px; margin: auto; border: 2px solid #eee; padding: 30px; border-radius: 10px; }
        .header { display: flex; justify-content: space-between; border-bottom: 2px solid #11998e; padding-bottom: 20px; }
        .logo { font-size: 28px; font-weight: bold; color: #11998e; }
        .dados-venda { margin: 30px 0; display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
        .item { padding: 10px; background: #f9f9f9; border-radius: 5px; }
        .label { font-size: 12px; color: #777; font-weight: bold; text-transform: uppercase; }
        .valor { font-size: 18px; font-weight: bold; }
        .total-box { margin-top: 30px; text-align: right; padding: 20px; background: #11998e; color: white; border-radius: 5px; }
        .footer { margin-top: 80px; text-align: center; font-size: 14px; }
        .assinaturas { margin-top: 60px; display: flex; justify-content: space-around; }
        .linha { border-top: 1px solid #333; width: 250px; margin-top: 40px; text-align: center; padding-top: 5px; }
        
        @media print {
            .no-print { display: none; }
            body { padding: 0; }
            .recibo-box { border: none; }
        }
    </style>
</head>
<body>

    <div class="no-print" style="margin-bottom: 20px; text-align: center;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #11998e; color: white; border: none; border-radius: 5px; cursor: pointer;">
            🖨️ Confirmar Impressão / Gerar PDF
        </button>
    </div>

    <div class="recibo-box">
        <div class="header">
            <div>
                <div class="logo">🚗 AutoManager Pro</div>
                <div><?= $v['loja_nome'] ?? 'Concessionária Matriz' ?></div>
            </div>
            <div style="text-align: right;">
                <div style="font-weight: bold;">RECIBO DE VENDA</div>
                <div style="color: #777;">#00<?= $v['id_venda'] ?></div>
            </div>
        </div>

        <div class="dados-venda">
            <div class="item">
                <div class="label">Data da Venda</div>
                <div class="valor"><?= date('d/m/Y H:i', strtotime($v['data_venda'])) ?></div>
            </div>
            <div class="item">
                <div class="label">Veículo</div>
                <div class="valor"><?= $v['veiculo_marca'] ?> <?= $v['veiculo_nome'] ?></div>
            </div>
        </div>

        <div class="item" style="margin-bottom: 20px;">
            <div class="label">Status do Pagamento</div>
            <div class="valor" style="color: #27ae60;">PAGO / FINALIZADO</div>
        </div>

        <div class="total-box">
            <div class="label" style="color: #eee;">Valor Total</div>
            <div style="font-size: 32px; font-weight: bold;">R$ <?= number_format($v['valor_total'], 2, ',', '.') ?></div>
        </div>

        <div class="assinaturas">
            <div class="linha">Vendedor</div>
            <div class="linha">Cliente</div>
        </div>

        <div class="footer">
            <p>Este documento serve como comprovante de transação comercial.<br>
            Obrigado por escolher a <strong>AutoManager Pro</strong>!</p>
        </div>
    </div>

    <script>
        // Aciona a impressão automaticamente ao carregar (opcional)
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>