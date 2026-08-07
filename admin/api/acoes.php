<?php
header('Content-Type: application/json');
require_once __DIR__ . '/../../config.php';
$pdo = conexao();

$input = json_decode(file_get_contents('php://input'), true);
$action = $input['action'] ?? '';

switch ($action) {
    case 'mudar_status':
        $stmt = $pdo->prepare("SELECT status FROM pedidos WHERE id = ?");
        $stmt->execute([$input['id']]);
        $pedido = $stmt->fetch();

        if (!$pedido) {
            echo json_encode(['erro' => 'Pedido não encontrado']);
            break;
        }

        if ($pedido['status'] === 'cancelado') {
            echo json_encode(['erro' => 'Pedido cancelado não pode ser alterado']);
            break;
        }

        $stmt = $pdo->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $stmt->execute([$input['status'], $input['id']]);
        echo json_encode(['sucesso' => true]);
        break;

    case 'criar_produto':
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, preco_p, preco_g, categoria_id, imagem) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$input['nome'], $input['descricao'], $input['preco'], $input['preco_p'] ?? null, $input['preco_g'] ?? null, $input['categoria_id'], $input['imagem'] ?? null]);
        echo json_encode(['sucesso' => true, 'id' => $pdo->lastInsertId()]);
        break;

    case 'editar_produto':
        if (!empty($input['imagem'])) {
            $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, preco_p = ?, preco_g = ?, categoria_id = ?, imagem = ? WHERE id = ?");
            $stmt->execute([$input['nome'], $input['descricao'], $input['preco'], $input['preco_p'] ?? null, $input['preco_g'] ?? null, $input['categoria_id'], $input['imagem'], $input['id']]);
        } else {
            $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, preco_p = ?, preco_g = ?, categoria_id = ? WHERE id = ?");
            $stmt->execute([$input['nome'], $input['descricao'], $input['preco'], $input['preco_p'] ?? null, $input['preco_g'] ?? null, $input['categoria_id'], $input['id']]);
        }
        echo json_encode(['sucesso' => true]);
        break;

    case 'excluir_produto':
        $stmt = $pdo->prepare("UPDATE produtos SET ativo = 0 WHERE id = ?");
        $stmt->execute([$input['id']]);
        echo json_encode(['sucesso' => true]);
        break;

    default:
        echo json_encode(['erro' => 'Ação inválida']);
}
