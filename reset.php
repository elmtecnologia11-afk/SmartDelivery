<?php
require_once 'config.php';
$pdo = conexao();
$pdo->exec('SET FOREIGN_KEY_CHECKS = 0');
$pdo->exec('DROP TABLE IF EXISTS pedido_itens');
$pdo->exec('DROP TABLE IF EXISTS pedidos');
$pdo->exec('DROP TABLE IF EXISTS produtos');
$pdo->exec('DROP TABLE IF EXISTS categorias');
$pdo->exec('SET FOREIGN_KEY_CHECKS = 1');
echo 'Tabelas dropping com sucesso! Acesse setup.php para recriar.';
