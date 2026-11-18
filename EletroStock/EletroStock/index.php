<?php
// Carrega todas as classes necessarias
require_once __DIR__ . '/models/ItemBase.php';
require_once __DIR__ . '/models/Categoria.php';
require_once __DIR__ . '/models/SecaoNominal.php';
require_once __DIR__ . '/models/Produto.php';
require_once __DIR__ . '/models/Movimentacao.php';
require_once __DIR__ . '/models/Repositorio.php';
require_once __DIR__ . '/controllers/EstoqueController.php';

$controller = new EstoqueController();
$controller->processarAcao();

$abaAtual = isset($_GET['aba']) ? $_GET['aba'] : 'estoque';
$controller->carregarView($abaAtual);