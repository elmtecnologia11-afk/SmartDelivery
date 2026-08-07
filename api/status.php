<?php
header('Content-Type: application/json');
require_once '../config.php';
$pdo = conexao();

$id = $_GET['id'] ?? null;

if (!$id) {
    echo json_encode(['erro' => 'ID nao informado']);
    exit;
}

$stmt = $pdo->prepare("SELECT status FROM pedidos WHERE id = ?");
$stmt->execute([$id]);
$pedido = $stmt->fetch();

if ($pedido) {
    echo json_encode(['status' => $pedido['status']]);
} else {
    echo json_encode(['status' => 'pendente']);
}
