<?php
header('Content-Type: application/json');
require_once '../config.php';
$pdo = conexao();

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['itens'])) {
    http_response_code(400);
    echo json_encode(['erro' => 'Pedido vazio']);
    exit;
}

try {
    $pdo->beginTransaction();

    $stmt = $pdo->prepare("INSERT INTO pedidos (cliente_nome, cliente_telefone, cliente_endereco, observacoes, pagamento, troco_para, total) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $input['nome'] ?? null,
        $input['telefone'] ?? null,
        $input['endereco'] ?? null,
        $input['observacoes'] ?? null,
        $input['pagamento'] ?? null,
        $input['troco'] ?? null,
        $input['total'] ?? 0
    ]);

    $pedidoId = $pdo->lastInsertId();

    $stmtItem = $pdo->prepare("INSERT INTO pedido_itens (pedido_id, produto_id, nome, tamanho, quantidade, preco_unitario) VALUES (?, ?, ?, ?, ?, ?)");
    foreach ($input['itens'] as $item) {
        $stmtItem->execute([
            $pedidoId,
            $item['id'],
            $item['nome'] ?? null,
            $item['tamanho'] ?? 'M',
            $item['qtd'],
            $item['preco']
        ]);
    }

    $pdo->commit();

    echo json_encode([
        'sucesso' => true,
        'pedido_id' => $pedidoId,
        'mensagem' => 'Pedido salvo com sucesso'
    ]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500);
    echo json_encode(['erro' => 'Erro ao salvar pedido: ' . $e->getMessage()]);
}
