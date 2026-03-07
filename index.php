<?php 
include 'conexao.php'; 

// --- ESTATÍSTICAS ---
$totalOS = $conn->query("SELECT COUNT(*) as total FROM OrdemDeServico")->fetch_assoc()['total'];
$pendentes = $conn->query("SELECT COUNT(*) as total FROM OrdemDeServico WHERE Status != 'Concluída'")->fetch_assoc()['total'];
$faturamento = $conn->query("SELECT SUM(Valor) as total FROM OrdemDeServico WHERE Status = 'Concluída'")->fetch_assoc()['total'] ?? 0;
$totalClientes = $conn->query("SELECT COUNT(*) as total FROM Clientes")->fetch_assoc()['total'];

// Busca OS Atrasadas (Data de entrega menor que hoje e não concluída)
$sqlAtrasadas = "SELECT COUNT(*) as total FROM OrdemDeServico WHERE Status != 'Concluída' AND DataEntrega < CURDATE()";
$totalAtrasadas = $conn->query($sqlAtrasadas)->fetch_assoc()['total'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoManager Pro - Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap'); 
        body { font-family: 'Inter', sans-serif; }
        
        .atraso-pulsar { animation: pulse-red 2s infinite; }
        @keyframes pulse-red {
            0% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
            70% { box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
            100% { box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
        }
    </style>
</head>
<body class="bg-slate-50 flex">

    <aside class="w-64 bg-slate-900 min-h-screen text-white p-6 hidden md:block sticky top-0 shadow-2xl">
        <div class="flex items-center gap-3 mb-10 border-b border-slate-700 pb-6">
            <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-900/50">
                <i class="fas fa-tools text-xl"></i>
            </div>
            <span class="text-xl font-black tracking-tighter italic">AUTOPRO <span class="text-blue-500 underline">OS</span></span>
        </div>
        <nav class="space-y-2">
            <a href="index.php" class="flex items-center gap-3 p-3 bg-blue-600 rounded-xl shadow-lg font-bold text-white transition-all"><i class="fas fa-chart-line w-5"></i> Dashboard</a>
            <a href="listar_clientes.php" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-xl transition text-slate-400 hover:text-white font-semibold"><i class="fas fa-users w-5"></i> Clientes</a>
            <a href="listar_veiculos.php" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-xl transition text-slate-400 hover:text-white font-semibold"><i class="fas fa-car w-5"></i> Veículos</a>
            <a href="listar_os.php" class="flex items-center gap-3 p-3 hover:bg-slate-800 rounded-xl transition text-slate-400 hover:text-white font-semibold"><i class="fas fa-list-check w-5"></i> Todas as OS</a>
        </nav>
    </aside>

    <main class="flex-1 p-8">
        <header class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h1 class="text-3xl font-black text-slate-800 tracking-tight italic">Painel de Controle</h1>
                <p class="text-slate-500 font-medium">Gerencie sua oficina com inteligência e precisão.</p>
            </div>
            <div class="flex items-center gap-4">
                <a href="nova_os.php" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-2xl font-black shadow-xl shadow-blue-200 flex items-center gap-2 transition active:scale-95 uppercase text-xs tracking-widest">
                    <i class="fas fa-plus"></i> Abrir Nova OS
                </a>
            </div>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-10">
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5">
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center text-xl"><i class="fas fa-file-invoice"></i></div>
                <div><p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Total OS</p><h3 class="text-2xl font-black text-slate-800"><?php echo $totalOS; ?></h3></div>
            </div>
            
            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5 relative">
                <div class="w-12 h-12 <?php echo ($totalAtrasadas > 0) ? 'bg-red-500 text-white atraso-pulsar' : 'bg-amber-50 text-amber-600'; ?> rounded-2xl flex items-center justify-center text-xl transition-all">
                    <i class="fas <?php echo ($totalAtrasadas > 0) ? 'fa-fire-alt' : 'fa-history'; ?>"></i>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Atrasadas / Pendentes</p>
                    <h3 class="text-2xl font-black text-slate-800">
                        <span class="<?php echo ($totalAtrasadas > 0) ? 'text-red-600' : ''; ?>"><?php echo $totalAtrasadas; ?></span> 
                        <span class="text-slate-300 font-light mx-1">/</span> 
                        <?php echo $pendentes; ?>
                    </h3>
                </div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border-b-4 border-b-green-500 flex items-center gap-5">
                <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center text-xl"><i class="fas fa-dollar-sign"></i></div>
                <div><p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Faturamento</p><h3 class="text-2xl font-black text-slate-800">R$ <?php echo number_format($faturamento, 2, ',', '.'); ?></h3></div>
            </div>

            <div class="bg-white p-6 rounded-3xl shadow-sm border border-slate-100 flex items-center gap-5">
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-2xl flex items-center justify-center text-xl"><i class="fas fa-user-friends"></i></div>
                <div><p class="text-slate-400 text-[10px] font-black uppercase tracking-widest">Clientes</p><h3 class="text-2xl font-black text-slate-800"><?php echo $totalClientes; ?></h3></div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="p-6 border-b border-slate-100 flex justify-between items-center bg-white">
                <div>
                    <h2 class="font-black text-slate-800 flex items-center gap-2 text-lg tracking-tighter italic">
                        <span class="w-2 h-6 bg-blue-600 rounded-full"></span>
                        Atividades Recentes
                    </h2>
                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest ml-4">Monitoramento de serviços em tempo real</p>
                </div>
                <a href="listar_os.php" class="bg-slate-50 hover:bg-slate-900 hover:text-white text-slate-600 px-4 py-2 rounded-xl text-xs font-black transition flex items-center gap-2 border border-slate-200">
                    VER TUDO <i class="fas fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50/50 text-slate-400 text-[10px] font-black uppercase tracking-[0.2em]">
                        <tr>
                            <th class="p-6">Identificação</th>
                            <th class="p-6">Veículo</th>
                            <th class="p-6">Status & Prazo</th>
                            <th class="p-6">Valor Total</th>
                            <th class="p-6 text-right">Gerenciar</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php
                        $sql = "SELECT OS.*, C.NomeCliente, V.Modelo, V.Marca 
                                FROM OrdemDeServico OS
                                LEFT JOIN Veiculos V ON OS.veiculo_id = V.idVeiculos
                                LEFT JOIN Clientes C ON V.Clientes_idClientes = C.idClientes
                                ORDER BY OS.idOrdemServico DESC LIMIT 8";
                        $res = $conn->query($sql);
                        
                        while($row = $res->fetch_assoc()):
                            $hoje = new DateTime();
                            $entrega = new DateTime($row['DataEntrega']);
                            $isAtrasado = ($hoje > $entrega && $row['Status'] != 'Concluída' && !empty($row['DataEntrega']));
                            $inicial = strtoupper(substr($row['NomeCliente'] ?? 'C', 0, 1));
                        ?>
                        <tr class="hover:bg-slate-50 transition-all group">
                            <td class="p-6">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-500 font-black text-sm border border-slate-200 group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-700 transition-all duration-300">
                                        <?php echo $inicial; ?>
                                    </div>
                                    <div>
                                        <p class="text-sm font-black text-slate-800 leading-none mb-1"><?php echo $row['NomeCliente'] ?? 'Consumidor'; ?></p>
                                        <span class="text-[11px] font-mono text-blue-600 font-bold tracking-tighter">#<?php echo $row['NumeroOS']; ?></span>
                                    </div>
                                </div>
                            </td>

                            <td class="p-6 text-xs font-semibold text-slate-600">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-car-side text-slate-300 group-hover:text-blue-500 transition-colors"></i>
                                    <?php echo ($row['Marca'] ? $row['Marca'].' ' : '') . ($row['Modelo'] ?? 'S/ Veículo'); ?>
                                </div>
                            </td>

                            <td class="p-6">
                                <div class="flex flex-col gap-1.5">
                                    <?php if($isAtrasado): ?>
                                        <span class="flex items-center gap-1.5 text-[9px] font-black text-red-600 bg-red-50 px-2 py-1 rounded-md w-fit border border-red-100 animate-pulse">
                                            <i class="fas fa-exclamation-triangle"></i> ATRASADA
                                        </span>
                                    <?php else: ?>
                                        <span class="flex items-center gap-1.5 text-[9px] font-black px-2 py-1 rounded-md w-fit border <?php 
                                            echo ($row['Status'] == 'Concluída') ? 'bg-green-50 text-green-700 border-green-100' : 'bg-amber-50 text-amber-700 border-amber-100'; ?>">
                                            <i class="fas fa-circle text-[6px] <?php echo ($row['Status'] == 'Concluída') ? 'text-green-600' : 'text-amber-500'; ?>"></i>
                                            <?php echo strtoupper($row['Status']); ?>
                                        </span>
                                    <?php endif; ?>
                                    <p class="text-[10px] text-slate-400 font-bold uppercase tracking-tighter">
                                        <?php echo date('d/m/Y', strtotime($row['DataEntrega'])); ?>
                                    </p>
                                </div>
                            </td>

                            <td class="p-6">
                                <p class="text-sm font-black text-slate-800">
                                    <span class="text-[10px] text-slate-400 font-normal mr-0.5">R$</span>
                                    <?php echo number_format($row['Valor'], 2, ',', '.'); ?>
                                </p>
                            </td>

                            <td class="p-6 text-right">
                                <div class="flex justify-end gap-2 translate-x-4 opacity-0 group-hover:translate-x-0 group-hover:opacity-100 transition-all duration-300">
                                    <a href="imprimir_os.php?id=<?php echo $row['idOrdemServico']; ?>" class="w-8 h-8 flex items-center justify-center bg-white text-slate-400 rounded-lg border border-slate-200 hover:text-blue-600 hover:border-blue-600 hover:shadow-lg transition-all" title="Imprimir">
                                        <i class="fas fa-print text-xs"></i>
                                    </a>
                                    <a href="editar_os.php?id=<?php echo $row['idOrdemServico']; ?>" class="w-8 h-8 flex items-center justify-center bg-white text-slate-400 rounded-lg border border-slate-200 hover:text-blue-600 hover:border-blue-600 hover:shadow-lg transition-all" title="Editar">
                                        <i class="fas fa-pen text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</body>
</html>
