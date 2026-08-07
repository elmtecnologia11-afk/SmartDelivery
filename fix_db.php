<?php
require_once 'config.php';
$pdo = conexao();

try {
    $pdo->exec("ALTER TABLE pedido_itens ADD COLUMN tamanho VARCHAR(10) DEFAULT 'M'");
    echo "Coluna tamanho adicionada com sucesso!";
} catch (PDOException $e) {
    if (stripos($e->getMessage(), 'duplicate column') !== false) {
        echo "Coluna tamanho ja existe.";
    } else {
        echo "Erro: " . $e->getMessage();
    }
}
