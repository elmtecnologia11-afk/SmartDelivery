<?php
require_once 'config.php';

try {
    $servidor = conexaoServidor();
    $servidor->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $servidor->exec("USE `" . DB_NAME . "`");

    $pdo = conexao();

    $pdo->exec("CREATE TABLE IF NOT EXISTS categorias (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL UNIQUE,
        ordem INT DEFAULT 0,
        ativo TINYINT DEFAULT 1
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS produtos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome VARCHAR(255) NOT NULL,
        descricao TEXT,
        preco DECIMAL(10,2) NOT NULL,
        preco_p DECIMAL(10,2) DEFAULT NULL,
        preco_g DECIMAL(10,2) DEFAULT NULL,
        categoria_id INT NOT NULL,
        imagem VARCHAR(255) DEFAULT NULL,
        ativo TINYINT DEFAULT 1,
        criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
        CONSTRAINT fk_produtos_categoria FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE RESTRICT
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    try {
        $pdo->exec("ALTER TABLE produtos ADD COLUMN preco_p DECIMAL(10,2) DEFAULT NULL");
    } catch (Exception $e) {}
    try {
        $pdo->exec("ALTER TABLE produtos ADD COLUMN preco_g DECIMAL(10,2) DEFAULT NULL");
    } catch (Exception $e) {}

    $pdo->exec("UPDATE produtos SET preco_p = ROUND(preco * 0.8, 2) WHERE preco_p IS NULL");
    $pdo->exec("UPDATE produtos SET preco_g = ROUND(preco * 1.3, 2) WHERE preco_g IS NULL");

    $pdo->exec("CREATE TABLE IF NOT EXISTS pedidos (
        id INT AUTO_INCREMENT PRIMARY KEY,
        cliente_nome VARCHAR(255),
        cliente_telefone VARCHAR(50),
        cliente_endereco VARCHAR(500),
        observacoes TEXT,
        pagamento VARCHAR(50) DEFAULT NULL,
        troco_para DECIMAL(10,2) DEFAULT NULL,
        total DECIMAL(10,2) NOT NULL,
        status VARCHAR(30) DEFAULT 'pendente',
        criado_em DATETIME DEFAULT CURRENT_TIMESTAMP,
        atualizado_em DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        CONSTRAINT chk_pedidos_status CHECK(status IN ('pendente','preparando','saiu_entrega','entregue','cancelado'))
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $pdo->exec("CREATE TABLE IF NOT EXISTS pedido_itens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        pedido_id INT NOT NULL,
        produto_id VARCHAR(50) DEFAULT NULL,
        nome VARCHAR(255),
        tamanho VARCHAR(10) DEFAULT 'M',
        quantidade INT NOT NULL DEFAULT 1,
        preco_unitario DECIMAL(10,2) NOT NULL,
        CONSTRAINT fk_itens_pedido FOREIGN KEY (pedido_id) REFERENCES pedidos(id) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    $cols = $pdo->query("SHOW COLUMNS FROM pedido_itens LIKE 'nome'")->fetchAll();
    if (empty($cols)) {
        $pdo->exec("ALTER TABLE pedido_itens ADD COLUMN nome VARCHAR(255)");
    }

    $cats = [
        ['Tradicionais', 1],
        ['Especiais', 2],
        ['Doces', 3],
        ['Bebidas', 4]
    ];
    $stmt = $pdo->prepare("INSERT IGNORE INTO categorias (nome, ordem) VALUES (?, ?)");
    foreach ($cats as $cat) {
        $stmt->execute($cat);
    }

    $count = $pdo->query("SELECT COUNT(*) FROM produtos")->fetchColumn();
    if ($count == 0) {
        $produtos = [
            ['Margherita', 'Molho de tomate, muçarela, manjericão fresco e azeite', 39.90, 1],
            ['Calabresa', 'Calabresa fatiada, cebola roxa, azeitona e muçarela', 42.90, 1],
            ['Quatro Queijos', 'Muçarela, provolone, parmesão e gorgonzola', 49.90, 1],
            ['Portuguesa', 'Presunto, ovo, cebola, azeitona, ervilha e muçarela', 44.90, 1],
            ['Frango com Catupiry', 'Frango desfiado, catupiry original e milho', 46.90, 2],
            ['Pepperoni', 'Pepperoni importado, muçarela e molho especial', 52.90, 2],
            ['Bacon Supreme', 'Bacon crocante, muçarela, cebola caramelizada e barbecue', 54.90, 2],
            ['Vegetariana', 'Berinjela, abobrinha, pimentão, tomate cereja e muçarela', 47.90, 2],
            ['Chocolate', 'Chocolate ao leite derretido e granulado', 39.90, 3],
            ['Romeu e Julieta', 'Goiabada cascão com muçarela', 42.90, 3],
            ['Banana com Canela', 'Banana fatiada, açúcar, canela e leite condensado', 41.90, 3],
            ['Coca-Cola 2L', 'Coca-Cola gelada 2 litros', 14.90, 4],
            ['Guaraná 2L', 'Guaraná Antarctica gelado 2 litros', 12.90, 4],
            ['Suco Natural', 'Suco de laranja natural 500ml', 8.90, 4],
        ];
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, categoria_id) VALUES (?, ?, ?, ?)");
        foreach ($produtos as $p) {
            $stmt->execute($p);
        }
    }

    echo "<h2>Banco configurado com sucesso!</h2>";
    echo "<p><a href='index.php'>Ver Loja</a> | <a href='admin/'>Painel Admin</a></p>";

} catch (PDOException $e) {
    echo "Erro ao configurar banco: " . $e->getMessage();
}
