<?php
$prodEditar = null;
if (isset($_GET['editar']) && isset($produtos[$_GET['editar']])) {
    $prodEditar = $produtos[$_GET['editar']];
}
?>

<!-- Cabeçalho da Seção -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-gray-300">
    <div>
        <h3 class="text-3xl font-semibold text-gray-800">Materiais Elétricos &bull; Produtos</h3>
        <p class="mt-1 text-sm text-gray-600">Cadastro de cabos, disjuntores, tomadas e catálogo geral do estoque.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="index.php?aba=secoes" class="inline-flex items-center px-4 py-2 text-sm font-medium text-indigo-700 bg-white border border-indigo-200 rounded-md hover:bg-indigo-50 shadow-sm transition">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Gerenciar Seções Nominais
        </a>
    </div>
</div>

<!-- Card de Formulário estilo v-dashboard -->
<div class="mt-6">
    <div class="bg-white rounded-md shadow-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h4 class="text-lg font-semibold text-gray-700 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                <?= $prodEditar ? 'Editar Material / Produto' : 'Cadastrar Novo Material' ?>
            </h4>
            <?php if ($prodEditar): ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    Modo Edição (ID: #<?= $prodEditar->getId() ?>)
                </span>
            <?php endif; ?>
        </div>

        <form method="POST" action="index.php?acao=salvar_produto" class="p-6">
            <?php if ($prodEditar): ?>
                <input type="hidden" name="id" value="<?= $prodEditar->getId() ?>">
            <?php endif; ?>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nome do Produto -->
                <div class="md:col-span-2">
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">
                        Nome do Produto / Material <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nome" name="nome" value="<?= $prodEditar ? htmlspecialchars($prodEditar->getNome()) : '' ?>" placeholder="Ex: Cabo Flexível 750V Vermelho ou Disjuntor Bipolar 32A" required class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition">
                </div>

                <!-- Categoria -->
                <div>
                    <label for="id_categoria" class="block text-sm font-medium text-gray-700 mb-1">
                        Categoria <span class="text-red-500">*</span>
                    </label>
                    <select id="id_categoria" name="id_categoria" required class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition">
                        <option value="">Selecione uma categoria...</option>
                        <?php foreach ($categorias as $cat): ?>
                            <option value="<?= $cat->getId() ?>" <?= ($prodEditar && $prodEditar->getIdCategoria() == $cat->getId()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat->getNome()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Seção Nominal (Opcional) -->
                <div>
                    <label for="id_secao" class="block text-sm font-medium text-gray-700 mb-1">
                        Seção Nominal / Bitola <span class="text-xs text-gray-400 font-normal">(opcional, p/ condutores)</span>
                    </label>
                    <select id="id_secao" name="id_secao" class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition">
                        <option value="">Nenhuma (Não aplicável)</option>
                        <?php foreach ($secoes as $sec): ?>
                            <option value="<?= $sec->getId() ?>" <?= ($prodEditar && $prodEditar->getIdSecaoNominal() == $sec->getId()) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($sec->getNome()) ?> <?= htmlspecialchars($sec->getUnidadeMedida()) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Preço Unitário -->
                <div>
                    <label for="preco" class="block text-sm font-medium text-gray-700 mb-1">
                        Preço Unitário (R$) <span class="text-red-500">*</span>
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">R$</span>
                        </div>
                        <input type="number" step="0.01" min="0" id="preco" name="preco" value="<?= $prodEditar ? $prodEditar->getPrecoUnitario() : '0.00' ?>" required class="w-full pl-10 pr-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center space-x-3">
                <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <?= $prodEditar ? 'Salvar Alterações' : 'Cadastrar Material' ?>
                </button>
                <?php if ($prodEditar): ?>
                    <a href="index.php?aba=produtos" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition">
                        Cancelar
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Tabela de Produtos Cadastrados estilo v-dashboard -->
<div class="mt-10">
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-xl font-semibold text-gray-700">Materiais Cadastrados no Catálogo</h4>
        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
            Total: <?= count($produtos) ?> materiais
        </span>
    </div>

    <div class="overflow-x-auto bg-white rounded-md shadow-md border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-indigo-800 text-gray-100 uppercase text-xs font-semibold tracking-wider">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Nome Completo</th>
                    <th class="px-6 py-3">Categoria</th>
                    <th class="px-6 py-3">Seção Nominal</th>
                    <th class="px-6 py-3 text-right">Preço Unitário</th>
                    <th class="px-6 py-3 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                <?php if (empty($produtos)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Nenhum produto cadastrado até o momento. Utilize o formulário acima para cadastrar.
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
                        ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-500">#<?= $p->getId() ?></td>
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
                            <td class="px-6 py-4 text-right font-medium text-gray-700">R$ <?= number_format($p->getPrecoUnitario(), 2, ',', '.') ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <a href="index.php?aba=produtos&editar=<?= $p->getId() ?>" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded border border-indigo-200 transition">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        Editar
                                    </a>
                                    <a href="index.php?acao=excluir_produto&id=<?= $p->getId() ?>" onclick="return confirm('Deseja realmente excluir este material do catálogo?')" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded border border-red-200 transition">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Excluir
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
