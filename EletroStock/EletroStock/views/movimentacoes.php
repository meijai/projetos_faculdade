<?php
$movEditar = null;
if (isset($_GET['editar']) && isset($movimentacoes[$_GET['editar']])) {
    $movEditar = $movimentacoes[$_GET['editar']];
}
?>

<!-- Cabeçalho da Seção -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-gray-300">
    <div>
        <h3 class="text-3xl font-semibold text-gray-800">Entradas e Saídas de Materiais</h3>
        <p class="mt-1 text-sm text-gray-600">Registro de movimentações no estoque: recebimento de compras ou requisições para obras.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="index.php?aba=historico" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 bg-white border border-indigo-200 rounded-md hover:bg-indigo-50 shadow-sm transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Ver Histórico Completo
        </a>
    </div>
</div>

<!-- Card de Formulário estilo v-dashboard -->
<div class="mt-6">
    <div class="bg-white rounded-md shadow-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h4 class="text-lg font-semibold text-gray-700 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                </svg>
                <?= $movEditar ? 'Editar Movimentação #' . $movEditar->getId() : 'Registrar Nova Movimentação' ?>
            </h4>
            <?php if ($movEditar): ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    Modo Edição (ID: #<?= $movEditar->getId() ?>)
                </span>
            <?php endif; ?>
        </div>

        <form method="POST" action="index.php?acao=movimentar" class="p-6">
            <?php if ($movEditar): ?>
                <input type="hidden" name="id" value="<?= $movEditar->getId() ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Tipo de Operação -->
                <div>
                    <label for="tipo" class="block text-sm font-medium text-gray-700 mb-1">
                        Tipo de Operação <span class="text-red-500">*</span>
                    </label>
                    <select id="tipo" name="tipo" required class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition font-medium">
                        <option value="ENTRADA" <?= ($movEditar && $movEditar->getTipo() === 'ENTRADA') ? 'selected' : '' ?> class="text-emerald-700 font-semibold">
                            &#8659; ENTRADA (Adicionar ao estoque)
                        </option>
                        <option value="SAIDA" <?= ($movEditar && $movEditar->getTipo() === 'SAIDA') ? 'selected' : '' ?> class="text-red-700 font-semibold">
                            &#8657; SAÍDA (Retirar do estoque)
                        </option>
                    </select>
                </div>

                <!-- Quantidade -->
                <div>
                    <label for="quantidade" class="block text-sm font-medium text-gray-700 mb-1">
                        Quantidade <span class="text-red-500">*</span>
                    </label>
                    <input type="number" id="quantidade" min="1" name="quantidade" value="<?= $movEditar ? $movEditar->getQuantidade() : '' ?>" placeholder="Ex: 10" required class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition">
                </div>

                <!-- Seleção e Filtro de Material -->
                <div class="md:col-span-2">
                    <label for="select_produto" class="block text-sm font-medium text-gray-700 mb-1">
                        Material Elétrico <span class="text-red-500">*</span>
                    </label>
                    <!-- Campo de filtro rápido -->
                    <div class="relative mb-2">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </span>
                        <input type="text" id="filtro_produto" placeholder="Digite para filtrar os materiais..." onkeyup="filtrarProdutos()" class="w-full pl-9 pr-4 py-1.5 text-xs bg-gray-50 border border-gray-300 rounded-md focus:bg-white focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 outline-none">
                    </div>
                    
                    <select name="id_produto" id="select_produto" required class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition">
                        <option value="">Selecione o material...</option>
                        <?php foreach ($produtos as $p): ?>
                            <option value="<?= $p->getId() ?>" <?= ($movEditar && $movEditar->getIdProduto() == $p->getId()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($p->getNomeExibicao($secoes)) ?> &bull; (Saldo atual: <?= $p->getQuantidade() ?> un.)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Motivo / Justificativa -->
                <div class="md:col-span-2">
                    <label for="motivo" class="block text-sm font-medium text-gray-700 mb-1">
                        Motivo / Justificativa / Documento <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="motivo" name="motivo" value="<?= $movEditar ? htmlspecialchars($movEditar->getMotivo()) : '' ?>" placeholder="Ex: NF 10234 Fornecedor Elétrica Brasil, Manutenção do Quadro Geral Bloco B, etc." required class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition">
                </div>
            </div>

            <div class="mt-6 flex items-center space-x-3">
                <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <?= $movEditar ? 'Salvar Alterações' : 'Registrar Operação' ?>
                </button>
                <?php if ($movEditar): ?>
                    <a href="index.php?aba=movimentacoes" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition">
                        Cancelar
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<script>
function filtrarProdutos() {
    let filter = document.getElementById('filtro_produto').value.toUpperCase();
    let options = document.getElementById('select_produto').getElementsByTagName('option');
    for (let i = 1; i < options.length; i++) {
        let txtValue = options[i].textContent || options[i].innerText;
        options[i].hidden = txtValue.toUpperCase().indexOf(filter) === -1;
    }
}
</script>

<!-- Tabela de Movimentações Recentes estilo v-dashboard -->
<div class="mt-10">
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-xl font-semibold text-gray-700">Movimentações Recentes</h4>
        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
            Total registrado: <?= count($movimentacoes) ?>
        </span>
    </div>

    <div class="overflow-x-auto bg-white rounded-md shadow-md border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-indigo-800 text-gray-100 uppercase text-xs font-semibold tracking-wider">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3 text-center">Tipo</th>
                    <th class="px-6 py-3">Material Elétrico</th>
                    <th class="px-6 py-3 text-center">Quantidade</th>
                    <th class="px-6 py-3">Motivo / Documento</th>
                    <th class="px-6 py-3 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                <?php if (empty($movimentacoes)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Nenhuma movimentação realizada até o momento.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach (array_reverse($movimentacoes, true) as $mov): ?>
                        <?php
                            $isEntrada = ($mov->getTipo() === 'ENTRADA');
                            $badgeClass = $isEntrada 
                                ? 'bg-emerald-100 text-emerald-800 border-emerald-200' 
                                : 'bg-rose-100 text-rose-800 border-rose-200';
                            $iconeSinal = $isEntrada ? '+' : '-';
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-500">#<?= $mov->getId() ?></td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border <?= $badgeClass ?>">
                                    <?= $mov->getTipo() ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <?= isset($produtos[$mov->getIdProduto()]) 
                                    ? htmlspecialchars($produtos[$mov->getIdProduto()]->getNomeExibicao($secoes)) 
                                    : '<span class="text-gray-400 italic">Item Removido</span>' ?>
                            </td>
                            <td class="px-6 py-4 text-center font-bold <?= $isEntrada ? 'text-emerald-700' : 'text-rose-700' ?>">
                                <?= $iconeSinal ?> <?= number_format($mov->getQuantidade(), 0, ',', '.') ?> un.
                            </td>
                            <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($mov->getMotivo()) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <a href="index.php?aba=movimentacoes&editar=<?= $mov->getId() ?>" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded border border-indigo-200 transition">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        Editar
                                    </a>
                                    <a href="index.php?acao=remover_movimentacao&id=<?= $mov->getId() ?>" onclick="return confirm('Deseja estornar esta movimentação? O saldo do produto será ajustado automaticamente no estoque.')" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 rounded border border-rose-200 transition">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Estornar
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
