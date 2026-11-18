<?php
$secaoEditar = null;
if (isset($_GET['editar']) && isset($secoes[$_GET['editar']])) {
    $secaoEditar = $secoes[$_GET['editar']];
}
?>

<!-- Cabeçalho da Seção -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-gray-300">
    <div>
        <h3 class="text-3xl font-semibold text-gray-800">Seções Nominais (Bitolas)</h3>
        <p class="mt-1 text-sm text-gray-600">Padronização de bitolas métricas em mm² para condutores elétricos.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="index.php?aba=produtos" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 bg-white border border-indigo-200 rounded-md hover:bg-indigo-50 shadow-sm transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Voltar aos Produtos
        </a>
    </div>
</div>

<!-- Card Informativo / Dica -->
<div class="mt-6 flex items-start p-4 bg-blue-50 border-l-4 border-blue-600 rounded-r-md text-sm text-blue-800 shadow-sm">
    <svg class="w-5 h-5 mr-3 flex-shrink-0 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </svg>
    <div>
        <p class="font-semibold">Regra de Padronização:</p>
        <p class="mt-0.5 text-blue-700">Utilize ponto (.) para valores decimais (ex: <strong>2.5</strong>, <strong>1.5</strong>). Valores inteiros (ex: <strong>4</strong>, <strong>10</strong>, <strong>16</strong>) são exibidos sem casas decimais. Entradas com vírgula ou valores duplicados não são aceitos.</p>
    </div>
</div>

<!-- Card de Formulário estilo v-dashboard -->
<div class="mt-6">
    <div class="bg-white rounded-md shadow-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h4 class="text-lg font-semibold text-gray-700 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"/>
                </svg>
                <?= $secaoEditar ? 'Editar Seção Nominal' : 'Cadastrar Nova Bitola' ?>
            </h4>
            <?php if ($secaoEditar): ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    Modo Edição (ID: #<?= $secaoEditar->getId() ?>)
                </span>
            <?php endif; ?>
        </div>

        <form method="POST" action="index.php?acao=salvar_secao" class="p-6">
            <?php if ($secaoEditar): ?>
                <input type="hidden" name="id" value="<?= $secaoEditar->getId() ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">
                        Valor da Seção <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nome" name="nome" placeholder="Ex: 2.5 ou 4 ou 10" value="<?= $secaoEditar ? htmlspecialchars($secaoEditar->getNome()) : '' ?>" required class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition">
                    <p class="mt-1 text-xs text-gray-500">Apenas números e ponto decimal.</p>
                </div>

                <div>
                    <label for="unidade_medida" class="block text-sm font-medium text-gray-700 mb-1">
                        Unidade de Medida <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="unidade_medida" name="unidade_medida" value="<?= $secaoEditar ? htmlspecialchars($secaoEditar->getUnidadeMedida()) : 'mm²' ?>" required class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition">
                    <p class="mt-1 text-xs text-gray-500">Padrão da ABNT/IEC: mm²</p>
                </div>
            </div>

            <div class="mt-6 flex items-center space-x-3">
                <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <?= $secaoEditar ? 'Salvar Alterações' : 'Cadastrar Bitola' ?>
                </button>
                <?php if ($secaoEditar): ?>
                    <a href="index.php?aba=secoes" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition">
                        Cancelar
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Tabela de Seções estilo v-dashboard -->
<div class="mt-10">
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-xl font-semibold text-gray-700">Bitolas Cadastradas</h4>
        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
            Total: <?= count($secoes) ?> seções
        </span>
    </div>

    <div class="overflow-x-auto bg-white rounded-md shadow-md border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-indigo-800 text-gray-100 uppercase text-xs font-semibold tracking-wider">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Seção Nominal</th>
                    <th class="px-6 py-3">Unidade de Medida</th>
                    <th class="px-6 py-3">Exibição Padrão</th>
                    <th class="px-6 py-3 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                <?php if (empty($secoes)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                            Nenhuma seção nominal cadastrada até o momento.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($secoes as $sec): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-500">#<?= $sec->getId() ?></td>
                            <td class="px-6 py-4 font-bold text-gray-900"><?= htmlspecialchars($sec->getNome()) ?></td>
                            <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($sec->getUnidadeMedida()) ?></td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-mono font-semibold bg-blue-100 text-blue-800 border border-blue-200">
                                    <?= htmlspecialchars($sec->getNome()) ?> <?= htmlspecialchars($sec->getUnidadeMedida()) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center space-x-2">
                                <a href="index.php?aba=secoes&editar=<?= $sec->getId() ?>" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded border border-indigo-200 transition">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                    </svg>
                                    Editar
                                </a>
                                <a href="index.php?acao=excluir_secao&id=<?= $sec->getId() ?>" onclick="return confirm('Deseja realmente excluir esta seção nominal?')" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded border border-red-200 transition">
                                    <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Excluir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
