<?php
require_once __DIR__ . '/../config.php';
$pdo = conexao();

// Buscar pedidos
$pedidos = $pdo->query("
    SELECT p.*, 
           GROUP_CONCAT(CONCAT(COALESCE(pi.nome, pr.nome), ' (', pi.tamanho, ') x', pi.quantidade) SEPARATOR ', ') as itens
    FROM pedidos p
    JOIN pedido_itens pi ON p.id = pi.pedido_id
    LEFT JOIN produtos pr ON pi.produto_id = pr.id
    GROUP BY p.id
    ORDER BY p.criado_em DESC
    LIMIT 50
")->fetchAll();

// Buscar produtos
$produtos = $pdo->query("
    SELECT p.*, c.nome as categoria_nome 
    FROM produtos p 
    JOIN categorias c ON p.categoria_id = c.id 
    ORDER BY c.ordem, p.nome
")->fetchAll();

$categorias = $pdo->query("SELECT * FROM categorias ORDER BY ordem")->fetchAll();

$statusLabels = [
    'pendente' => ['Pendente', '#f39c12'],
    'preparando' => ['Preparando', '#3498db'],
    'saiu_entrega' => ['Saiu p/ Entrega', '#9b59b6'],
    'entregue' => ['Entregue', '#27ae60'],
    'cancelado' => ['Cancelado', '#e74c3c'],
];

$pagamentoLabels = [
    'dinheiro' => ['Dinheiro', '&#128176;'],
    'credito' => ['Cartão Crédito', '&#128179;'],
    'debito' => ['Cartão Débito', '&#128179;'],
    'pix' => ['Pix', '&#128241;'],
    'vale' => ['Vale Refeição', '&#127973;'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin - <?= NOME_LOJA ?></title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: #f0f2f5; color: #1a1a2e; }
    .admin-header { background: #1a1a2e; color: #fff; padding: 16px 24px; display: flex; justify-content: space-between; align-items: center; }
    .admin-header h1 { font-size: 1.3rem; }
    .admin-header a { color: #fff; text-decoration: none; font-size: 0.9rem; opacity: 0.8; }
    .admin-header a:hover { opacity: 1; }
    .tabs { display: flex; gap: 4px; padding: 16px 24px 0; background: #fff; border-bottom: 1px solid #e0e0e0; }
    .tab { padding: 12px 24px; border: none; background: none; font-size: 0.95rem; font-weight: 600; cursor: pointer; border-bottom: 3px solid transparent; color: #666; }
    .tab.active { color: #c41e1e; border-bottom-color: #c41e1e; }
    .tab-content { display: none; padding: 24px; }
    .tab-content.active { display: block; }
    .container { max-width: 1200px; margin: 0 auto; }

    /* Pedidos */
    .pedido-card { background: #fff; border-radius: 10px; padding: 16px; margin-bottom: 12px; box-shadow: 0 2px 6px rgba(0,0,0,0.05); }
    .pedido-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
    .pedido-id { font-weight: 700; font-size: 1rem; }
    .pedido-status { padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; color: #fff; }
    .pedido-info { font-size: 0.85rem; color: #555; margin-bottom: 4px; }
    .pedido-itens { font-size: 0.85rem; color: #333; margin: 8px 0; padding: 8px; background: #f8f8f8; border-radius: 6px; }
    .pedido-total { font-weight: 700; color: #c41e1e; font-size: 1rem; }
    .pedido-bloqueado { font-weight: 600; color: #b91c1c; margin-top: 10px; }
    .pedido-acoes { display: flex; gap: 6px; margin-top: 10px; flex-wrap: wrap; }
    .pedido-acoes-linha { display: flex; justify-content: space-between; align-items: flex-start; gap: 10px; margin-top: 10px; flex-wrap: wrap; }
    .btn-whats-enviar { background: #25d366; color: #fff; border: none; padding: 8px 16px; border-radius: 6px; font-size: 0.85rem; font-weight: 700; cursor: pointer; transition: background 0.2s; }
    .btn-whats-enviar:hover { background: #1eb354; }
    .btn-status { padding: 6px 12px; border: none; border-radius: 6px; font-size: 0.78rem; font-weight: 600; cursor: pointer; color: #fff; }
    .btn-pendente { background: #f39c12; }
    .btn-preparando { background: #3498db; }
    .btn-saiu_entrega { background: #9b59b6; }
    .btn-entregue { background: #27ae60; }
    .btn-cancelado { background: #e74c3c; }

    /* Produtos */
    .produtos-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
    .btn-novo { background: #c41e1e; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; }
    .tabela { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; }
    .tabela th, .tabela td { padding: 12px 16px; text-align: left; border-bottom: 1px solid #eee; }
    .tabela th { background: #1a1a2e; color: #fff; font-size: 0.85rem; }
    .tabela td { font-size: 0.9rem; }
    .tabela tr:hover { background: #f8f8f8; }
    .btn-editar { background: #3498db; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; }
    .btn-excluir { background: #e74c3c; color: #fff; border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; }

    /* Modal */
    .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.5); display: none; z-index: 100; }
    .modal-overlay.active { display: flex; align-items: center; justify-content: center; }
    .modal { background: #fff; border-radius: 12px; padding: 24px; width: 90%; max-width: 500px; }
    .modal h3 { margin-bottom: 16px; }
    .modal input, .modal select, .modal textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 8px; margin-bottom: 12px; font-family: inherit; font-size: 0.9rem; }
    .modal-acoes { display: flex; gap: 10px; justify-content: flex-end; margin-top: 16px; }
    .btn-salvar { background: #27ae60; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; flex: 1; }
    .btn-cancelar { background: #eee; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; flex: 1; }
    .btn-anexar { background: #3498db; color: #fff; border: none; padding: 10px 20px; border-radius: 8px; font-weight: 600; cursor: pointer; flex: 1; }
    .btn-anexar:hover { background: #2980b9; }
    .campo-form { margin-bottom: 14px; }
    .campo-label { display: block; font-size: 0.85rem; font-weight: 600; color: #333; margin-bottom: 4px; }
    .precos-tamanhos { display: flex; gap: 10px; }
    .preco-tamanho { flex: 1; }
    .campo-label.pequeno { font-size: 0.75rem; color: #666; font-weight: 500; }
    .preco-ajuda { font-size: 0.75rem; color: #999; }
    @media (max-width: 768px) {
      .precos-tamanhos { flex-direction: column; gap: 8px; }
    }

    @media (max-width: 768px) {
      .tabela { display: block; overflow-x: auto; }
      .pedido-acoes { flex-direction: column; }
    }
  </style>
</head>
<body>

  <div class="admin-header">
    <h1><?= NOME_LOJA ?> - Admin</h1>
    <a href="../index.php">&larr; Voltar à Loja</a>
  </div>

  <div class="tabs">
    <button class="tab active" onclick="showTab('pedidos')">Pedidos</button>
    <button class="tab" onclick="showTab('produtos')">Produtos</button>
  </div>

  <!-- PEDIDOS -->
  <div class="tab-content active" id="tab-pedidos">
    <div class="container">
      <?php if (empty($pedidos)): ?>
        <p style="text-align:center;color:#999;padding:40px;">Nenhum pedido encontrado</p>
      <?php endif; ?>
      <?php foreach ($pedidos as $p): ?>
        <div class="pedido-card">
          <div class="pedido-header">
            <span class="pedido-id">#<?= $p['id'] ?> - <?= $p['cliente_nome'] ?: 'Sem nome' ?></span>
            <span class="pedido-status" style="background:<?= $statusLabels[$p['status']][1] ?>">
              <?= $statusLabels[$p['status']][0] ?>
            </span>
          </div>
          <div class="pedido-info">&#128222; <?= $p['cliente_telefone'] ?: '-' ?> | &#128205; <?= $p['cliente_endereco'] ?: '-' ?></div>
          <div class="pedido-itens"><?= $p['itens'] ?></div>
          <?php if ($p['observacoes']): ?>
            <div class="pedido-info"><strong>Obs:</strong> <?= $p['observacoes'] ?></div>
          <?php endif; ?>
          <?php if ($p['pagamento']): ?>
            <div class="pedido-info">
              <strong>Pagamento:</strong> 
              <?= $pagamentoLabels[$p['pagamento']][1] ?> <?= $pagamentoLabels[$p['pagamento']][0] ?>
              <?php if ($p['pagamento'] === 'dinheiro' && $p['troco_para']): ?>
                | <strong>Troco para:</strong> R$ <?= number_format($p['troco_para'], 2, ',', '.') ?>
                | <strong>Troco a devolver:</strong> R$ <?= number_format($p['troco_para'] - $p['total'], 2, ',', '.') ?>
              <?php endif; ?>
            </div>
          <?php endif; ?>
          <div class="pedido-total">Total: R$ <?= number_format($p['total'], 2, ',', '.') ?></div>
          <div class="pedido-acoes-linha">
            <div class="pedido-acoes">
              <?php if ($p['status'] === 'cancelado'): ?>
                <div class="pedido-info pedido-bloqueado">&#128274; Pedido cancelado — status não pode ser alterado.</div>
              <?php else: ?>
                <?php foreach ($statusLabels as $key => $label): ?>
                  <button class="btn-status btn-<?= $key ?>" onclick="mudarStatus(<?= $p['id'] ?>, '<?= $key ?>')">
                    <?= $label[0] ?>
                  </button>
                <?php endforeach; ?>
              <?php endif; ?>
            </div>
            <button class="btn-whats-enviar" onclick="enviarLinkWhats(<?= $p['id'] ?>, '<?= addslashes($p['cliente_telefone']) ?>')">&#128172; Enviar Link</button>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>

  <!-- PRODUTOS -->
  <div class="tab-content" id="tab-produtos">
    <div class="container">
      <div class="produtos-header">
        <h2>Produtos</h2>
        <button class="btn-novo" onclick="abrirModal()">+ Novo Produto</button>
      </div>
      <table class="tabela">
        <thead>
          <tr>
            <th>Imagem</th>
            <th>ID</th>
            <th>Nome</th>
            <th>Categoria</th>
            <th>Preço P</th>
            <th>Preço M</th>
            <th>Preço G</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($produtos as $p): ?>
          <tr>
            <td>
              <?php if ($p['imagem']): ?>
                <img src="/ <?= $p['imagem'] ?>" alt="<?= $p['nome'] ?>" class="produto-thumb" onerror="this.style.display='none'">
              <?php else: ?>
                <span class="sem-imagem">Sem foto</span>
              <?php endif; ?>
            </td>
            <td><?= $p['id'] ?></td>
            <td><?= $p['nome'] ?></td>
            <td><?= $p['categoria_nome'] ?></td>
            <td>R$ <?= number_format($p['preco_p'], 2, ',', '.') ?></td>
            <td>R$ <?= number_format($p['preco'], 2, ',', '.') ?></td>
            <td>R$ <?= number_format($p['preco_g'], 2, ',', '.') ?></td>
            <td>
              <button class="btn-editar" onclick="editarProduto(<?= $p['id'] ?>, '<?= addslashes($p['nome']) ?>', '<?= addslashes($p['descricao']) ?>', <?= $p['preco'] ?>, <?= $p['preco_p'] ?>, <?= $p['preco_g'] ?>, <?= $p['categoria_id'] ?>)">Editar</button>
              <button class="btn-excluir" onclick="excluirProduto(<?= $p['id'] ?>)">Excluir</button>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- MODAL PRODUTO -->
  <div class="modal-overlay" id="modal">
    <div class="modal">
      <h3 id="modalTitulo">Novo Produto</h3>
      <form id="formProduto" enctype="multipart/form-data">
        <input type="hidden" id="produtoId">
        <div class="campo-form">
          <label class="campo-label">Nome do Produto</label>
          <input type="text" id="produtoNome" placeholder="Ex: Pizza Margherita" required>
        </div>
        <div class="campo-form">
          <label class="campo-label">Descrição</label>
          <textarea id="produtoDescricao" placeholder="Descrição do produto" rows="2"></textarea>
        </div>
        <div class="campo-form">
          <label class="campo-label">Preços por Tamanho (R$)</label>
          <div class="precos-tamanhos">
            <div class="preco-tamanho">
              <label class="campo-label pequeno">P</label>
              <input type="number" id="produtoPrecoP" placeholder="0,00" step="0.01" min="0" required>
            </div>
            <div class="preco-tamanho">
              <label class="campo-label pequeno">M</label>
              <input type="number" id="produtoPreco" placeholder="0,00" step="0.01" min="0" required>
            </div>
            <div class="preco-tamanho">
              <label class="campo-label pequeno">G</label>
              <input type="number" id="produtoPrecoG" placeholder="0,00" step="0.01" min="0" required>
            </div>
          </div>
        </div>
        <div class="campo-form">
          <label class="campo-label">Categoria</label>
          <select id="produtoCategoria" required>
            <?php foreach ($categorias as $cat): ?>
              <option value="<?= $cat['id'] ?>"><?= $cat['nome'] ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="campo-form">
          <label class="campo-label">Imagem</label>
          <div class="upload-area">
            <label for="produtoImagem" class="upload-label">
              <span id="uploadTexto">&#128247; Selecionar imagem</span>
              <input type="file" id="produtoImagem" accept="image/*" style="display:none;" onchange="previewImagem(this)">
            </label>
            <div id="uploadPreview" class="upload-preview"></div>
          </div>
        </div>
        <div class="modal-acoes">
          <button type="button" class="btn-anexar" onclick="document.getElementById('produtoImagem').click()">&#128206; Anexar</button>
          <button type="button" class="btn-cancelar" onclick="fecharModal()">Cancelar</button>
          <button type="submit" class="btn-salvar">Salvar</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    function showTab(tab) {
      document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
      document.querySelectorAll('.tab-content').forEach(t => t.classList.remove('active'));
      document.getElementById('tab-' + tab).classList.add('active');
      event.target.classList.add('active');
    }

    function mudarStatus(id, status) {
      fetch('api/acoes.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'mudar_status', id: id, status: status })
      }).then(r => r.json()).then(d => {
        if (d.sucesso) location.reload();
      });
    }

    function enviarLinkWhats(id, telefone) {
      const tel = (telefone || '').replace(/\D/g, '');
      const link = window.location.origin + '/rastreio.php?id=' + id;
      const url = 'https://wa.me/' + tel + '?text=' + encodeURIComponent(link);
      window.open(url, '_blank');
    }

    function previewImagem(input) {
      const preview = document.getElementById('uploadPreview');
      const texto = document.getElementById('uploadTexto');
      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
          preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview">';
          texto.textContent = 'Trocar imagem';
        };
        reader.readAsDataURL(input.files[0]);
      }
    }

    function abrirModal() {
      document.getElementById('modalTitulo').textContent = 'Novo Produto';
      document.getElementById('formProduto').reset();
      document.getElementById('produtoId').value = '';
      document.getElementById('produtoPreco').value = '';
      document.getElementById('produtoPrecoP').value = '';
      document.getElementById('produtoPrecoG').value = '';
      document.getElementById('uploadPreview').innerHTML = '';
      document.getElementById('uploadTexto').textContent = 'Selecionar imagem';
      document.getElementById('modal').classList.add('active');
    }

    function editarProduto(id, nome, desc, precoM, precoP, precoG, cat) {
      document.getElementById('modalTitulo').textContent = 'Editar Produto';
      document.getElementById('produtoId').value = id;
      document.getElementById('produtoNome').value = nome;
      document.getElementById('produtoDescricao').value = desc;
      document.getElementById('produtoPreco').value = precoM;
      document.getElementById('produtoPrecoP').value = precoP;
      document.getElementById('produtoPrecoG').value = precoG;
      document.getElementById('produtoCategoria').value = cat;
      document.getElementById('uploadPreview').innerHTML = '';
      document.getElementById('uploadTexto').textContent = 'Selecionar imagem';
      document.getElementById('modal').classList.add('active');
    }

    function fecharModal() {
      document.getElementById('modal').classList.remove('active');
    }

    document.getElementById('formProduto').addEventListener('submit', async function(e) {
      e.preventDefault();
      const id = document.getElementById('produtoId').value;
      const imagemInput = document.getElementById('produtoImagem');
      let caminhoImagem = '';

      if (imagemInput.files && imagemInput.files[0]) {
        const formData = new FormData();
        formData.append('imagem', imagemInput.files[0]);
        try {
          const uploadRes = await fetch('api/upload.php', { method: 'POST', body: formData });
          const uploadData = await uploadRes.json();
          if (uploadData.sucesso) {
            caminhoImagem = uploadData.caminho;
          } else {
            alert('Erro ao enviar imagem: ' + uploadData.erro);
            return;
          }
        } catch (err) {
          alert('Erro ao enviar imagem');
          return;
        }
      }

      const dados = {
        action: id ? 'editar_produto' : 'criar_produto',
        id: id || null,
        nome: document.getElementById('produtoNome').value,
        descricao: document.getElementById('produtoDescricao').value,
        preco: document.getElementById('produtoPreco').value,
        preco_p: document.getElementById('produtoPrecoP').value,
        preco_g: document.getElementById('produtoPrecoG').value,
        categoria_id: document.getElementById('produtoCategoria').value,
        imagem: caminhoImagem
      };
      fetch('api/acoes.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(dados)
      }).then(r => r.json()).then(d => {
        if (d.sucesso) location.reload();
      });
    });

    function excluirProduto(id) {
      if (!confirm('Excluir este produto?')) return;
      fetch('api/acoes.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ action: 'excluir_produto', id: id })
      }).then(r => r.json()).then(d => {
        if (d.sucesso) location.reload();
      });
    }
  </script>

</body>
</html>
