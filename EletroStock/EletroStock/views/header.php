<?php
$aba = isset($aba) ? $aba : (isset($_GET['aba']) ? $_GET['aba'] : 'estoque');

// Helper para classes dos links da sidebar do v-dashboard
if (!function_exists('navClass')) {
    function navClass($currentAba, $targetAba) {
        if ($currentAba === $targetAba) {
            return 'bg-gray-600 bg-opacity-25 text-gray-100 border-indigo-500 border-l-4';
        }
        return 'border-gray-900 border-l-4 text-gray-400 hover:bg-gray-600 hover:bg-opacity-25 hover:text-gray-100';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EletroStock - Gestão de Materiais Elétricos</title>
    <!-- Google Fonts: Roboto & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN com plugins -->
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography,aspect-ratio"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        roboto: ['Roboto', 'sans-serif'],
                    },
                    colors: {
                        indigo: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }
        ::-webkit-scrollbar-track {
            background: #1f2937;
        }
        ::-webkit-scrollbar-thumb {
            background: #4b5563;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #6b7280;
        }
    </style>
</head>
<body class="font-roboto bg-gray-200 antialiased">
    <div class="flex h-screen bg-gray-200">
        <!-- Backdrop mobile para Sidebar -->
        <div id="sidebar-backdrop" class="fixed inset-0 z-20 transition-opacity bg-black opacity-50 hidden lg:hidden" onclick="toggleSidebar()"></div>

        <!-- Sidebar v-dashboard -->
        <aside id="sidebar" class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto transition duration-300 transform -translate-x-full bg-gray-900 lg:translate-x-0 lg:static lg:inset-0">
            <!-- Logo EletroStock -->
            <div class="flex items-center justify-center mt-8">
                <a href="index.php?aba=estoque" class="flex items-center space-x-2.5 group">
                    <span class="flex items-center justify-center w-10 h-10 text-2xl bg-amber-500 bg-opacity-20 text-amber-400 rounded-lg shadow-sm group-hover:scale-110 transition-transform">⚡</span>
                    <span class="text-2xl font-bold tracking-wide text-white">EletroStock</span>
                </a>
            </div>

            <!-- Navegação da Sidebar -->
            <nav class="mt-8 space-y-1">
                <!-- Estoque / Dashboard -->
                <a class="flex items-center px-6 py-3 transition-colors duration-200 <?= navClass($aba, 'estoque') ?>" href="index.php?aba=estoque">
                    <svg class="w-5 h-5" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M2 10C2 5.58172 5.58172 2 10 2V10H18C18 14.4183 14.4183 18 10 18C5.58172 18 2 14.4183 2 10Z" fill="currentColor"/>
                        <path d="M12 2.25195C14.8113 2.97552 17.0245 5.18877 17.748 8.00004H12V2.25195Z" fill="currentColor"/>
                    </svg>
                    <span class="mx-4 font-medium">Dashboard / Estoque</span>
                </a>

                <!-- Produtos / Materiais -->
                <a class="flex items-center px-6 py-3 transition-colors duration-200 <?= navClass($aba, 'produtos') ?>" href="index.php?aba=produtos">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <span class="mx-4 font-medium">Produtos</span>
                </a>

                <!-- Categorias -->
                <a class="flex items-center px-6 py-3 transition-colors duration-200 <?= navClass($aba, 'categorias') ?>" href="index.php?aba=categorias">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    <span class="mx-4 font-medium">Categorias</span>
                </a>

                <!-- Entradas e Saídas -->
                <a class="flex items-center px-6 py-3 transition-colors duration-200 <?= navClass($aba, 'movimentacoes') ?>" href="index.php?aba=movimentacoes">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <span class="mx-4 font-medium">Entradas e Saídas</span>
                </a>

                <!-- Histórico Geral -->
                <a class="flex items-center px-6 py-3 transition-colors duration-200 <?= navClass($aba, 'historico') ?>" href="index.php?aba=historico">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="mx-4 font-medium">Histórico Geral</span>
                </a>
            </nav>
        </aside>

        <!-- Área de Conteúdo Principal -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Header Superior v-dashboard -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b-4 border-indigo-600 shadow-sm">
                <div class="flex items-center space-x-4">
                    <!-- Botão Toggle Menu Mobile -->
                    <button class="text-gray-500 hover:text-gray-700 focus:outline-none lg:hidden" onclick="toggleSidebar()" aria-label="Abrir Menu">
                        <svg class="w-6 h-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M4 6H20M4 12H20M4 18H11" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </button>

                    <!-- Barra de Pesquisa Rápida v-dashboard -->
                    <div class="relative">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-gray-400">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none">
                                <path d="M21 21L15 15M17 10C17 13.866 13.866 17 10 17C6.13401 17 3 13.866 3 10C3 6.13401 6.13401 3 10 3C13.866 3 17 6.13401 17 10Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </span>
                        <input id="global-search" class="w-48 sm:w-72 pl-10 pr-4 py-2 text-sm text-gray-700 placeholder-gray-400 bg-gray-50 border border-gray-200 rounded-md focus:border-indigo-600 focus:bg-white focus:outline-none focus:ring focus:ring-opacity-40 focus:ring-indigo-500 transition" type="text" placeholder="Filtrar dados da página..." onkeyup="handleGlobalSearch(event)">
                    </div>
                </div>

                <div class="flex items-center">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800 border border-emerald-200">
                        <span class="w-2 h-2 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                        Estoque Online
                    </span>
                </div>
            </header>

            <!-- Container do Conteúdo com Scroll -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-200">
                <div class="container mx-auto px-6 py-8">
                    <!-- Mensagens de Alerta Estilizadas -->
                    <?php if (!empty($_SESSION['mensagem_erro'])): ?>
                        <div id="alert-erro" class="flex items-center justify-between p-4 mb-6 text-sm text-red-800 bg-red-100 border-l-4 border-red-600 rounded-r-md shadow-sm">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-3 flex-shrink-0 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                <span><strong>Atenção:</strong> <?= htmlspecialchars($_SESSION['mensagem_erro']); unset($_SESSION['mensagem_erro']); ?></span>
                            </div>
                            <button onclick="document.getElementById('alert-erro').remove()" class="text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    <?php endif; ?>
