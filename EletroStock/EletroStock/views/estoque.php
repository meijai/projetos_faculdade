<?php
// Métricas para os Cards estilo v-dashboard
$totalProdutos = count($produtos);
$totalCategorias = count($categorias);
$saldoTotalItens = 0;
$valorTotalEstoque = 0;

foreach ($produtos as $p) {
    $saldoTotalItens += $p->getQuantidade();
    $valorTotalEstoque += ($p->getQuantidade() * $p->getPrecoUnitario());
}
?>

<!-- Cabeçalho da Página -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-gray-300">
    <div>
        <h3 class="text-3xl font-semibold text-gray-800">Dashboard de Estoque</h3>
        <p class="mt-1 text-sm text-gray-600">Visão consolidada do inventário de materiais elétricos e valor patrimonial.</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-3">
        <a href="index.php?aba=movimentacoes" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            Nova Movimentação
        </a>
        <a href="index.php?aba=produtos" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 bg-white border border-indigo-200 rounded-md hover:bg-indigo-50 shadow-sm transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
            </svg>
            Cadastrar Produto
        </a>
    </div>
</div>

<!-- Métricas Cards no estilo v-dashboard -->
<div class="mt-6">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1: Saldo de Itens -->
        <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-md border-l-4 border-indigo-600">
            <div class="p-3 bg-indigo-600 bg-opacity-75 rounded-full text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="mx-5">
                <h4 class="text-2xl font-bold text-gray-800"><?= number_format($saldoTotalItens, 0, ',', '.') ?></h4>
                <div class="text-sm font-medium text-gray-500">Unidades em Estoque</div>
            </div>
        </div>

        <!-- Card 2: Valor Total Estimado -->
        <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-md border-l-4 border-blue-600">
            <div class="p-3 bg-blue-600 bg-opacity-75 rounded-full text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
            <div class="mx-5">
                <h4 class="text-2xl font-bold text-gray-800">R$ <?= number_format($valorTotalEstoque, 2, ',', '.') ?></h4>
                <div class="text-sm font-medium text-gray-500">Valor Total do Estoque</div>
            </div>
        </div>

        <!-- Card 3: Produtos Cadastrados -->
        <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-md border-l-4 border-emerald-600">
            <div class="p-3 bg-emerald-600 bg-opacity-75 rounded-full text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <div class="mx-5">
                <h4 class="text-2xl font-bold text-gray-800"><?= $totalProdutos ?></h4>
                <div class="text-sm font-medium text-gray-500">Produtos Cadastrados</div>
            </div>
        </div>

        <!-- Card 4: Categorias -->
        <div class="flex items-center px-5 py-6 bg-white rounded-md shadow-md border-l-4 border-purple-600">
            <div class="p-3 bg-purple-600 bg-opacity-75 rounded-full text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
            </div>
            <div class="mx-5">
                <h4 class="text-2xl font-bold text-gray-800"><?= $totalCategorias ?></h4>
                <div class="text-sm font-medium text-gray-500">Categorias Ativas</div>
            </div>
        </div>
    </div>
</div>

<!-- Tabela de Estoque Atual no estilo v-dashboard -->
<div class="mt-8">
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-xl font-semibold text-gray-700">Materiais Elétricos em Estoque</h4>
        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
            Total de registros: <?= count($produtos) ?>
        </span>
    </div>

    <div class="overflow-x-auto bg-white rounded-md shadow-md border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-indigo-800 text-gray-100 uppercase text-xs font-semibold tracking-wider">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Nome do Material</th>
                    <th class="px-6 py-3">Categoria</th>
                    <th class="px-6 py-3">Seção Nominal</th>
                    <th class="px-6 py-3 text-center">Saldo Atual</th>
                    <th class="px-6 py-3 text-right">Preço Unitário</th>
                    <th class="px-6 py-3 text-right">Total Estimado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                <?php if (empty($produtos)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-gray-500">
                            Nenhum material elétrico cadastrado no momento. 
                            <a href="index.php?aba=produtos" class="text-indigo-600 hover:underline font-medium">Cadastrar primeiro item</a>.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($produtos as $p): ?>
                        <?php 
                            $catNome = isset($categorias[$p->getIdCategoria()]) ? $categorias[$p->getIdCategoria()]->getNome() : 'N/A';
                            $secaoTexto = '-';
                            if ($p->getIdSecaoNominal() && isset($secoes[$p->getIdSecaoNominal()])) {
                                $sec = $secoes[$p->getIdSecaoNominal()];
                                $secaoTexto = $sec->getNome() . ' ' . $sec->getUnidadeMedida();
                            }
                            $qtd = $p->getQuantidade();
                            $preco = $p->getPrecoUnitario();
                            $subtotal = $qtd * $preco;

                            // Badge de status do saldo
                            if ($qtd > 10) {
                                $badgeSaldo = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                            } elseif ($qtd > 0) {
                                $badgeSaldo = 'bg-amber-100 text-amber-800 border-amber-200';
                            } else {
                                $badgeSaldo = 'bg-red-100 text-red-800 border-red-200';
                            }
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs text-gray-500 font-semibold">#<?= $p->getId() ?></td>
                            <td class="px-6 py-4 font-medium text-gray-900"><?= htmlspecialchars($p->getNomeExibicao($secoes)) ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 border border-gray-200">
                                    <?= htmlspecialchars($catNome) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <?php if ($secaoTexto !== '-'): ?>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono font-medium bg-blue-50 text-blue-700 border border-blue-200">
                                        <?= htmlspecialchars($secaoTexto) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold border <?= $badgeSaldo ?>">
                                    <?= number_format($qtd, 0, ',', '.') ?> un.
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium text-gray-700">R$ <?= number_format($preco, 2, ',', '.') ?></td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($produtos)): ?>
                <tfoot>
                    <tr class="bg-gray-50 font-semibold text-gray-800 border-t-2 border-gray-300">
                        <td colspan="4" class="px-6 py-4 text-right uppercase text-xs tracking-wider">Total Geral do Inventário:</td>
                        <td class="px-6 py-4 text-center font-bold text-indigo-700"><?= number_format($saldoTotalItens, 0, ',', '.') ?> un.</td>
                        <td class="px-6 py-4"></td>
                        <td class="px-6 py-4 text-right font-bold text-emerald-700 text-base">R$ <?= number_format($valorTotalEstoque, 2, ',', '.') ?></td>
                    </tr>
                </tfoot>
            <?php endif; ?>
        </table>
    </div>
</div>
