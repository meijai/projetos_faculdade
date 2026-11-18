<?php
$catEditar = null;
if (isset($_GET['editar']) && isset($categorias[$_GET['editar']])) {
    $catEditar = $categorias[$_GET['editar']];
}
?>

<!-- Cabeçalho da Seção -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-gray-300">
    <div>
        <h3 class="text-3xl font-semibold text-gray-800">Categorias de Materiais</h3>
        <p class="mt-1 text-sm text-gray-600">Organize os materiais elétricos por famílias como Condutores, Proteção, Iluminação, etc.</p>
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

<!-- Card de Formulário estilo v-dashboard -->
<div class="mt-6">
    <div class="bg-white rounded-md shadow-md border border-gray-200 overflow-hidden">
        <div class="px-6 py-4 bg-gray-50 border-b border-gray-200 flex items-center justify-between">
            <h4 class="text-lg font-semibold text-gray-700 flex items-center">
                <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <?= $catEditar ? 'Editar Categoria' : 'Cadastrar Nova Categoria' ?>
            </h4>
            <?php if ($catEditar): ?>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-100 text-amber-800">
                    Modo Edição (ID: #<?= $catEditar->getId() ?>)
                </span>
            <?php endif; ?>
        </div>

        <form method="POST" action="index.php?acao=salvar_categoria" class="p-6">
            <?php if ($catEditar): ?>
                <input type="hidden" name="id" value="<?= $catEditar->getId() ?>">
            <?php endif; ?>

            <div class="space-y-4">
                <div>
                    <label for="nome" class="block text-sm font-medium text-gray-700 mb-1">
                        Nome da Categoria <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="nome" name="nome" value="<?= $catEditar ? htmlspecialchars($catEditar->getNome()) : '' ?>" placeholder="Ex: Cabos e Fios, Disjuntores, Tomadas e Interruptores" required class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition">
                </div>

                <div>
                    <label for="descricao" class="block text-sm font-medium text-gray-700 mb-1">
                        Descrição Detalhada <span class="text-red-500">*</span>
                    </label>
                    <textarea id="descricao" name="descricao" rows="3" required placeholder="Descreva os tipos de materiais pertencentes a este grupo..." class="w-full px-4 py-2 text-sm border border-gray-300 rounded-md focus:ring-2 focus:ring-indigo-500 focus:border-indigo-600 outline-none transition"><?= $catEditar ? htmlspecialchars($catEditar->getDescricao()) : '' ?></textarea>
                </div>
            </div>

            <div class="mt-6 flex items-center space-x-3">
                <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 shadow focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <?= $catEditar ? 'Salvar Alterações' : 'Cadastrar Categoria' ?>
                </button>
                <?php if ($catEditar): ?>
                    <a href="index.php?aba=categorias" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-md transition">
                        Cancelar
                    </a>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Tabela de Categorias estilo v-dashboard -->
<div class="mt-10">
    <div class="flex items-center justify-between mb-4">
        <h4 class="text-xl font-semibold text-gray-700">Categorias Cadastradas</h4>
        <span class="text-xs font-medium text-gray-500 bg-gray-100 px-3 py-1 rounded-full border border-gray-200">
            Total: <?= count($categorias) ?> categorias
        </span>
    </div>

    <div class="overflow-x-auto bg-white rounded-md shadow-md border border-gray-200">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-indigo-800 text-gray-100 uppercase text-xs font-semibold tracking-wider">
                    <th class="px-6 py-3">ID</th>
                    <th class="px-6 py-3">Nome da Categoria</th>
                    <th class="px-6 py-3">Descrição</th>
                    <th class="px-6 py-3 text-center">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 text-sm">
                <?php if (empty($categorias)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                            Nenhuma categoria cadastrada até o momento.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($categorias as $cat): ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 font-mono text-xs font-semibold text-gray-500">#<?= $cat->getId() ?></td>
                            <td class="px-6 py-4 font-semibold text-gray-900"><?= htmlspecialchars($cat->getNome()) ?></td>
                            <td class="px-6 py-4 text-gray-600"><?= htmlspecialchars($cat->getDescricao()) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="inline-flex items-center justify-center gap-2">
                                    <a href="index.php?aba=categorias&editar=<?= $cat->getId() ?>" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-indigo-700 bg-indigo-50 hover:bg-indigo-100 rounded border border-indigo-200 transition">
                                        <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/>
                                        </svg>
                                        Editar
                                    </a>
                                    <a href="index.php?acao=excluir_categoria&id=<?= $cat->getId() ?>" onclick="return confirm('Deseja realmente excluir a categoria?')" class="inline-flex items-center px-2.5 py-1 text-xs font-medium text-red-700 bg-red-50 hover:bg-red-100 rounded border border-red-200 transition">
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
