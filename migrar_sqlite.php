<?php
require_once 'config.php';

$sqliteFile = __DIR__ . '/data/smartdelivery.db';
if (!file_exists($sqliteFile)) {
    die("Arquivo SQLite nao encontrado.");
}

function num($v) {
    return ($v === null || $v === '') ? null : (float)$v;
}
function txt($v) {
    return ($v === null || $v === '') ? null : $v;
}

try {
    $sqlite = new PDO('sqlite:' . $sqliteFile);
    $sqlite->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $mysql = conexao();

    $mysql->exec("SET FOREIGN_KEY_CHECKS = 0");
    $mysql->exec("TRUNCATE TABLE pedido_itens");
    $mysql->exec("TRUNCATE TABLE pedidos");
    $mysql->exec("TRUNCATE TABLE produtos");
    $mysql->exec("TRUNCATE TABLE categorias");
    $mysql->exec("ALTER TABLE pedidos AUTO_INCREMENT = 1");
    $mysql->exec("ALTER TABLE produtos AUTO_INCREMENT = 1");
    $mysql->exec("ALTER TABLE categorias AUTO_INCREMENT = 1");
    $mysql->exec("SET FOREIGN_KEY_CHECKS = 1");

    $total = ['categorias' => 0, 'produtos' => 0, 'pedidos' => 0, 'pedido_itens' => 0];

    $stmt = $mysql->prepare("INSERT INTO categorias (id, nome, ordem, ativo) VALUES (?, ?, ?, ?)");
    foreach ($sqlite->query("SELECT * FROM categorias") as $r) {
        $stmt->execute([$r['id'], $r['nome'], $r['ordem'] ?? 0, $r['ativo'] ?? 1]);
        $total['categorias']++;
    }

    $stmt = $mysql->prepare("INSERT INTO produtos (id, nome, descricao, preco, preco_p, preco_g, categoria_id, imagem, ativo, criado_em) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($sqlite->query("SELECT * FROM produtos") as $r) {
        $stmt->execute([
            $r['id'], $r['nome'], txt($r['descricao']), num($r['preco']),
            num($r['preco_p']), num($r['preco_g']), $r['categoria_id'],
            txt($r['imagem']), $r['ativo'] ?? 1, txt($r['criado_em']) ?: date('Y-m-d H:i:s')
        ]);
        $total['produtos']++;
    }

    $stmt = $mysql->prepare("INSERT INTO pedidos (id, cliente_nome, cliente_telefone, cliente_endereco, observacoes, pagamento, troco_para, total, status, criado_em, atualizado_em) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    foreach ($sqlite->query("SELECT * FROM pedidos") as $r) {
        $stmt->execute([
            $r['id'], txt($r['cliente_nome']), txt($r['cliente_telefone']), txt($r['cliente_endereco']),
            txt($r['observacoes']), txt($r['pagamento']), num($r['troco_para']), num($r['total']),
            $r['status'], txt($r['criado_em']) ?: date('Y-m-d H:i:s'), txt($r['atualizado_em']) ?: (txt($r['criado_em']) ?: date('Y-m-d H:i:s'))
        ]);
        $total['pedidos']++;
    }

    $stmt = $mysql->prepare("INSERT INTO pedido_itens (id, pedido_id, produto_id, nome, tamanho, quantidade, preco_unitario) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($sqlite->query("SELECT * FROM pedido_itens") as $r) {
        $stmt->execute([
            $r['id'], $r['pedido_id'], $r['produto_id'] === '' ? null : $r['produto_id'], txt($r['nome']),
            txt($r['tamanho']) ?: 'M', $r['quantidade'], num($r['preco_unitario'])
        ]);
        $total['pedido_itens']++;
    }

    foreach ($total as $tabela => $qtd) {
        echo "$tabela: $qtd registros migrados<br>";
    }
    echo "Migracao concluida com sucesso!";

} catch (Exception $e) {
    die("Erro na migracao: " . $e->getMessage());
}
