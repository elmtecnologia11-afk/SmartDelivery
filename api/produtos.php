<?php
header('Content-Type: application/json');
require_once '../config.php';
$pdo = conexao();

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'listar':
        $stmt = $pdo->query("
            SELECT p.*, c.nome as categoria_nome 
            FROM produtos p 
            JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.ativo = 1 
            ORDER BY c.ordem, p.nome
        ");
        echo json_encode($stmt->fetchAll());
        break;

    case 'categorias':
        $stmt = $pdo->query("SELECT * FROM categorias WHERE ativo = 1 ORDER BY ordem");
        echo json_encode($stmt->fetchAll());
        break;

    default:
        echo json_encode(['erro' => 'Ação inválida']);
}
