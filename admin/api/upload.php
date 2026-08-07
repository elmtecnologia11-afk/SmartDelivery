<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['erro' => 'Metodo nao permitido']);
    exit;
}

if (!isset($_FILES['imagem'])) {
    echo json_encode(['erro' => 'Nenhuma imagem enviada']);
    exit;
}

$arquivo = $_FILES['imagem'];

if ($arquivo['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['erro' => 'Erro no upload: ' . $arquivo['error']]);
    exit;
}

$extensoesPermitidas = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$extensao = strtolower(pathinfo($arquivo['name'], PATHINFO_EXTENSION));

if (!in_array($extensao, $extensoesPermitidas)) {
    echo json_encode(['erro' => 'Extensao nao permitida. Use: jpg, jpeg, png, gif ou webp']);
    exit;
}

$tamanhoMaximo = 5 * 1024 * 1024; // 5MB
if ($arquivo['size'] > $tamanhoMaximo) {
    echo json_encode(['erro' => 'Imagem muito grande. Maximo 5MB']);
    exit;
}

$pastaDestino = __DIR__ . '/../../images/';
if (!is_dir($pastaDestino)) {
    mkdir($pastaDestino, 0755, true);
}

$nomeArquivo = 'produto_' . time() . '_' . uniqid() . '.' . $extensao;
$caminhoCompleto = $pastaDestino . $nomeArquivo;

if (move_uploaded_file($arquivo['tmp_name'], $caminhoCompleto)) {
    echo json_encode([
        'sucesso' => true,
        'caminho' => 'images/' . $nomeArquivo,
        'mensagem' => 'Imagem enviada com sucesso'
    ]);
} else {
    echo json_encode(['erro' => 'Erro ao salvar imagem']);
}
