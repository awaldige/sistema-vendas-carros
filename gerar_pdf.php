<?php
require_once 'dompdf/autoload.inc.php'; // Caminho para a biblioteca
include('conexao.php');

use Dompdf\Dompdf;
use Dompdf\Options;

// 1. Pegar o ID da venda
$id_venda = $_GET['id'] ?? 0;

// 2. Buscar dados completos da venda, carro e loja
$sql = "SELECT v.*, c.ano, mo.nome_modelo, ma.nome_marca, l.nome as loja_nome 
        FROM Venda v
        JOIN Carro c ON v.id_carro = c.id_carro
        JOIN Modelo mo ON c.id_modelo = mo.id_modelo
        JOIN Marca ma ON mo.id_marca = ma.id_marca
        JOIN Loja l ON c.id_loja = l.id_loja
        WHERE v.id_venda = $id_venda";

$res = $conn->query($sql);
$dados = $res->fetch_assoc();

if (!$dados) die("Venda não encontrada.");

// 3. Configurar o PDF
$options = new Options();
$options->set('isHtml5ParserEnabled', true);
$dompdf = new Dompdf($options);

// 4. Montar o HTML do Recibo
$html = "
<style>
    body { font-family: sans-serif; color: #333; }
    .header { text-align: center; border-bottom: 2px solid #333; padding-bottom: 10px; }
    .recibo-titulo { font-size: 24px; font-weight: bold; margin-top: 20px; }
    .info-section { margin-top: 30px; }
    .info-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    .info-table td { padding: 10px; border: 1px solid #eee; }
    .valor-total { font-size: 20px; color: #27ae60; font-weight: bold; text-align: right; margin-top: 40px; }
    .footer { margin-top: 100px; text-align: center; font-size: 12px; }
    .assinatura { margin-top: 50px; border-top: 1px solid #000; display: inline-block; width: 250px; }
</style>

<div class='header'>
    <h1>AUTO MANAGER PRO</h1>
    <p>{$dados['loja_nome']}</p>
</div>

<div style='text-align: center;'>
    <p class='recibo-titulo'>RECIBO DE VENDA #{$dados['id_venda']}</p>
</div>

<div class='info-section'>
    <table class='info-table'>
        <tr>
            <td><strong>Veículo:</strong> {$dados['nome_marca']} {$dados['nome_modelo']}</td>
            <td><strong>Ano:</strong> {$dados['ano']}</td>
        </tr>
        <tr>
            <td><strong>Data da Venda:</strong> " . date('d/m/Y H:i', strtotime($dados['data_venda'])) . "</td>
            <td><strong>Vendedor:</strong> Sistema Automático</td>
        </tr>
    </table>
</div>

<div class='valor-total'>
    VALOR TOTAL: R$ " . number_format($dados['valor_total'], 2, ',', '.') . "
</div>

<div class='footer'>
    <div class='assinatura'>Assinatura do Responsável</div>
    <p style='margin-top: 30px;'>Obrigado pela preferência!</p>
</div>
";

// 5. Gerar o arquivo
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// 6. Enviar para o navegador (download automático)
$dompdf->stream("recibo_venda_{$id_venda}.pdf", ["Attachment" => false]);
?>