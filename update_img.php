<?php
require_once 'config.php';
$pdo = conexao();
$stmt = $pdo->prepare("UPDATE produtos SET imagem = ? WHERE nome LIKE ?");
$stmt->execute(['images/pizza_calabresa_e_mussarela_4389_orig.jpg', '%Calabresa%']);
echo $stmt->rowCount() . ' produto atualizado';
