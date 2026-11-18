<!-- Cabeçalho da Seção -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-gray-300">
    <div>
        <h3 class="text-3xl font-semibold text-gray-800">Histórico Geral de Movimentações</h3>
        <p class="mt-1 text-sm text-gray-600">Trilha de auditoria completa de todas as entradas, compras, retiradas e requisições.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="index.php?aba=movimentacoes" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nova Movimentação
        </a>
    </div>
</div>

<!-- Tabela de Auditoria Cronológica estilo v-dashboard -->
<div class="mt-6">
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-xl font-semibold text-gray-700">Registros em Ordem Cronológica</h4>
        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
            Total de eventos: <?= count($movimentacoes) ?>
        </span>
    </div>

    <div class="overflow-x-auto bg-white rounded-md shadow-md border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-indigo-800 text-gray-100 uppercase text-xs font-semibold tracking-wider">
                    <th class="px-6 py-3">Data e Hora</th>
                    <th class="px-6 py-3 text-center">Tipo</th>
                    <th class="px-6 py-3">Material Elétrico</th>
                    <th class="px-6 py-3 text-center">Quantidade</th>
                    <th class="px-6 py-3">Justificativa / Motivo</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                <?php if (empty($movimentacoes)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <p class="font-medium">Nenhuma movimentação registrada até o momento.</p>
                            <p class="text-xs text-gray-400 mt-1">Todas as operações de entrada e saída efetuadas no sistema aparecerão listadas aqui.</p>
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
                            <td class="px-6 py-4 whitespace-nowrap text-xs font-mono text-gray-500">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <?= htmlspecialchars($mov->getDataHora()) ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold border <?= $badgeClass ?>">
                                    <?= $mov->getTipo() ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 font-medium text-gray-900">
                                <?= isset($produtos[$mov->getIdProduto()]) 
                                    ? htmlspecialchars($produtos[$mov->getIdProduto()]->getNomeExibicao($secoes)) 
                                    : '<span class="text-gray-400 italic">Produto não localizado</span>' ?>
                            </td>
                            <td class="px-6 py-4 text-center font-bold <?= $isEntrada ? 'text-emerald-700' : 'text-rose-700' ?>">
                                <?= $iconeSinal ?> <?= number_format($mov->getQuantidade(), 0, ',', '.') ?> un.
                            </td>
                            <td class="px-6 py-4 text-gray-600">
                                <?= htmlspecialchars($mov->getMotivo()) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
