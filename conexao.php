<?php
// Ativa o relatório de erros para facilitar o diagnóstico
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Detecta se você está no computador local ou no servidor online
$is_localhost = ($_SERVER['SERVER_NAME'] == 'localhost' || $_SERVER['SERVER_NAME'] == '127.0.0.1');

try {
    if ($is_localhost) {
        // --- CONFIGURAÇÕES LOCAL (SEU COMPUTADOR) ---
        $host = "127.0.0.1";
        $user = "root";
        $pass = ""; 
        $db   = "vendascarros"; 
        $port = 3308; 
        
        $conn = new mysqli($host, $user, $pass, $db, $port);
    } else {
        // --- CONFIGURAÇÕES ONLINE (INFINITYFREE) ---
        // Dados extraídos do seu painel:
        $host = "sql313.infinityfree.com"; 
        $user = "if0_41153277";
        $pass = "Awaldige785143";
        $db   = "if0_41153277_AutoManagerPro";
        
        // No servidor online, geralmente não passamos a porta
        $conn = new mysqli($host, $user, $pass, $db);
    }

    $conn->set_charset("utf8mb4");

} catch (Exception $e) {
    die("Erro crítico de conexão: " . $e->getMessage());
}
?>
