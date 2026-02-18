<?php
include('conexao.php');

// Verifica se o ID foi passado na URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara a query de exclusão (ajuste o nome da coluna ID se necessário)
    // Geralmente é id_carro ou id
    $sql = "DELETE FROM Carro WHERE id_carro = $id";

    if ($conn->query($sql)) {
        // Redireciona de volta para o index com sucesso
        header("Location: index.php?msg=excluido");
    } else {
        echo "Erro ao excluir: " . $conn->error;
    }
} else {
    header("Location: index.php");
}
?>