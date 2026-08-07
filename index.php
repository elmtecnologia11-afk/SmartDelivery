<?php
require_once 'config.php';
$pdo = conexao();

$categorias = $pdo->query("SELECT * FROM categorias WHERE ativo = 1 ORDER BY ordem")->fetchAll();
$produtos = $pdo->query("
    SELECT p.*, c.nome as categoria_nome 
    FROM produtos p 
    JOIN categorias c ON p.categoria_id = c.id 
    WHERE p.ativo = 1 
    ORDER BY c.ordem, p.nome
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= NOME_LOJA ?> - Faça seu pedido</title>
  <link rel="stylesheet" href="style.css">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

  <header class="header">
    <div class="container header-content">
      <div class="logo-area">
        <img src="images/logo.png" alt="<?= NOME_LOJA ?>" class="logo-img" onerror="this.style.display='none'">
        <div>
          <h1 class="nome-loja"><?= NOME_LOJA ?></h1>
          <span class="tagline">A melhor pizza da cidade</span>
        </div>
      </div>
      <div class="header-actions">
        <span class="horario">&#128336; <?= HORARIO_FUNCIONAMENTO ?></span>
        <button class="carrinho-btn" id="carrinhoBtn">
          &#128722; <span class="carrinho-count" id="carrinhoCount">0</span>
        </button>
      </div>
    </div>
  </header>

  <main class="container main-content">
    <div class="search-bar">
      <span class="search-icon">&#128269;</span>
      <input type="text" id="buscaProduto" placeholder="Buscar pizza, bebida..." class="search-input">
      <button type="button" class="search-clear" id="buscaClear" style="display:none;">&times;</button>
    </div>

    <nav class="categorias" id="categorias">
      <button class="cat-btn active" data-categoria="todos">Todos</button>
      <?php foreach ($categorias as $cat): ?>
        <button class="cat-btn" data-categoria="<?= $cat['id'] ?>"><?= $cat['nome'] ?></button>
      <?php endforeach; ?>
    </nav>

    <div class="produtos" id="produtos">
      <?php foreach ($produtos as $p): ?>
        <div class="produto-card" data-categoria="<?= $p['categoria_id'] ?>">
          <img src="<?= $p['imagem'] ?: 'https://via.placeholder.com/400x200/eee/999?text=' . urlencode($p['nome']) ?>" 
               alt="<?= $p['nome'] ?>" class="produto-img"
               onerror="this.src='https://via.placeholder.com/400x200/eee/999?text=<?= urlencode($p['nome']) ?>'">
          <div class="produto-info">
            <div class="produto-nome"><?= $p['nome'] ?></div>
            <div class="produto-desc"><?= $p['descricao'] ?></div>
            <div class="tamanhos-opcoes">
              <button class="tamanho-btn active" data-tamanho="P" data-preco="<?= $p['preco_p'] ?>" onclick="selecionarTamanho(this)">P <small>R$ <?= number_format($p['preco_p'], 2, ',', '.') ?></small></button>
              <button class="tamanho-btn" data-tamanho="M" data-preco="<?= $p['preco'] ?>" onclick="selecionarTamanho(this)">M <small>R$ <?= number_format($p['preco'], 2, ',', '.') ?></small></button>
              <button class="tamanho-btn" data-tamanho="G" data-preco="<?= $p['preco_g'] ?>" onclick="selecionarTamanho(this)">G <small>R$ <?= number_format($p['preco_g'], 2, ',', '.') ?></small></button>
            </div>
            <div class="produto-footer">
              <span class="produto-preco" id="preco-<?= $p['id'] ?>">R$ <?= number_format($p['preco'], 2, ',', '.') ?></span>
              <button class="btn-adicionar" 
                      onclick="adicionarAoCarrinho(<?= $p['id'] ?>, '<?= addslashes($p['nome']) ?>', <?= $p['preco'] ?>)">
                Adicionar
              </button>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>

  <div class="carrinho-panel" id="carrinhoPanel">
    <div class="carrinho-header">
      <h3>Seu Pedido</h3>
      <button class="fechar-carrinho" id="fecharCarrinho">&times;</button>
    </div>
    <div class="carrinho-items" id="carrinhoItems">
      <p class="carrinho-vazio">Seu carrinho está vazio</p>
    </div>
    <div class="carrinho-footer" id="carrinhoFooter" style="display:none;">
      <div class="total">
        <span>Total:</span>
        <span id="totalPreco">R$ 0,00</span>
      </div>
      <div class="checkout-form">
        <input type="text" id="clienteNome" placeholder="Seu nome" class="input-field">
        <input type="tel" id="clienteTelefone" placeholder="WhatsApp" class="input-field">
        <textarea id="clienteEndereco" placeholder="Endereço completo" class="input-field" rows="2"></textarea>
        <textarea id="observacaoPedido" placeholder="Observações (ex: sem cebola...)" class="input-field" rows="2"></textarea>
        <div class="pagamento-titulo">Forma de Pagamento</div>
        <div class="pagamento-opcoes">
          <label class="pagamento-option">
            <input type="radio" name="pagamento" value="dinheiro" checked>
            <span>&#128176; Dinheiro</span>
          </label>
          <label class="pagamento-option">
            <input type="radio" name="pagamento" value="credito">
            <span>&#128179; Cartão Crédito</span>
          </label>
          <label class="pagamento-option">
            <input type="radio" name="pagamento" value="debito">
            <span>&#128179; Cartão Débito</span>
          </label>
          <label class="pagamento-option">
            <input type="radio" name="pagamento" value="pix">
            <span>&#128241; Pix</span>
          </label>
          <label class="pagamento-option">
            <input type="radio" name="pagamento" value="vale">
            <span>&#127973; Vale Refeição</span>
          </label>
        </div>
      </div>
      <button class="btn-finalizar" id="finalizarPedido">Finalizar Pedido</button>
    </div>
  </div>

  <div class="overlay" id="overlay"></div>

  <!-- Popup Meia Pizza -->
  <div class="meia-pizza-modal" id="meiaPizzaModal">
    <div class="meia-pizza-content">
      <div class="meia-pizza-header">
        <h3>&#127829; Dividir Pizza?</h3>
      </div>
      <div class="meia-pizza-body">
        <p>Você já tem <strong id="meiaSaborExistente"></strong> (G) no carrinho.</p>
        <p>Deseja dividir o sabor com <strong id="meiaSaborNovo"></strong>?</p>
        <div class="meia-pizza-preview">
          <span id="meiaSaborExistentePreview"></span>
          <span class="meia-pizza-divider">+</span>
          <span id="meiaSaborNovoPreview"></span>
        </div>
      </div>
      <div class="meia-pizza-footer">
        <button class="btn-nao" id="btnNaoMeia">N&atilde;o, cada um seu sabor</button>
        <button class="btn-sim" id="btnSimMeia">Sim, dividir</button>
      </div>
    </div>
  </div>

  <!-- Popup 3 Sabores -->
  <div class="meia-pizza-modal" id="tresPizzaModal">
    <div class="meia-pizza-content">
      <div class="meia-pizza-header header-tres">
        <h3>&#127829; Dividir em 3 Sabores?</h3>
      </div>
      <div class="meia-pizza-body">
        <p>Você já tem <strong id="tresSaboresExistentes"></strong> (G) no carrinho.</p>
        <p>Deseja dividir o sabor com <strong id="tresSaborNovo"></strong>?</p>
        <div class="meia-pizza-preview preview-tres">
          <span id="tresSaboresExistentesPreview"></span>
          <span class="meia-pizza-divider">+</span>
          <span id="tresSaborNovoPreview"></span>
        </div>
      </div>
      <div class="meia-pizza-footer">
        <button class="btn-nao" id="btnNaoTres">N&atilde;o, cada um seu sabor</button>
        <button class="btn-sim" id="btnSimTres">Sim, dividir</button>
      </div>
    </div>
  </div>

  <!-- Popup 4 Sabores -->
  <div class="meia-pizza-modal" id="quatroPizzaModal">
    <div class="meia-pizza-content">
      <div class="meia-pizza-header header-quatro">
        <h3>&#127829; Dividir em 4 Sabores?</h3>
      </div>
      <div class="meia-pizza-body">
        <p>Você já tem <strong id="quatroSaboresExistentes"></strong> (G) no carrinho.</p>
        <p>Deseja dividir o sabor com <strong id="quatroSaborNovo"></strong>?</p>
        <div class="meia-pizza-preview preview-quatro">
          <span id="quatroSaboresExistentesPreview"></span>
          <span class="meia-pizza-divider">+</span>
          <span id="quatroSaborNovoPreview"></span>
        </div>
      </div>
      <div class="meia-pizza-footer">
        <button class="btn-nao" id="btnNaoQuatro">N&atilde;o, cada um seu sabor</button>
        <button class="btn-sim" id="btnSimQuatro">Sim, dividir</button>
      </div>
    </div>
  </div>

  <!-- Popup Troco -->
  <div class="meia-pizza-modal" id="trocoModal">
    <div class="meia-pizza-content">
      <div class="meia-pizza-header header-troco">
        <h3>&#128176; Pagamento em Dinheiro</h3>
      </div>
      <div class="meia-pizza-body">
        <p>Total do pedido: <strong class="troco-total-numero" id="trocoTotal">R$ 0,00</strong></p>
        <p>Precisa de troco?</p>
        <div class="troco-valor-box" id="trocoValorBox" style="display:none;">
          <label class="campo-label">Valor que vai pagar (R$)</label>
          <input type="text" id="trocoValorInput" class="input-field" placeholder="Ex: 50,00" inputmode="decimal">
          <div class="troco-calculo" id="trocoCalculo"></div>
        </div>
      </div>
      <div class="meia-pizza-footer">
        <button class="btn-nao" id="btnTrocoNao">N&atilde;o precisa</button>
        <button class="btn-sim" id="btnTrocoSim">Sim, preciso</button>
        <button class="btn-sim" id="btnTrocoConfirmar" style="display:none;">Confirmar troco</button>
      </div>
    </div>
  </div>

  <!-- Tela Pedido Realizado -->
  <div class="pedido-modal" id="pedidoModal">
    <div class="pedido-modal-content">
      <div class="pedido-modal-header">
        <div class="pedido-check">&#10003;</div>
        <h2>Pedido Realizado!</h2>
        <p class="pedido-numero">Pedido <strong>#<span id="pedidoNumero"></span></strong></p>
      </div>
      <div class="pedido-modal-body">
        <div class="pedido-resumo" id="pedidoResumo"></div>
        <div class="rastreio-enviado-box">
          <p class="rastreio-enviado-msg">&#9989; Link de acompanhamento enviado para o WhatsApp da loja.</p>
        </div>
        <div class="timeline">
          <div class="timeline-item active" id="step-recebido">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <strong>Pedido Recebido</strong>
              <span>Aguardando preparo</span>
            </div>
          </div>
          <div class="timeline-item" id="step-preparando">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <strong>Preparando</strong>
              <span>Sua pizza esta sendo feita</span>
            </div>
          </div>
          <div class="timeline-item" id="step-entrega">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <strong>Saiu para Entrega</strong>
              <span>A caminho do seu endereco</span>
            </div>
          </div>
          <div class="timeline-item" id="step-entregue">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <strong>Entregue</strong>
              <span>Bom apetite!</span>
            </div>
          </div>
        </div>
      </div>
      <div class="pedido-modal-footer">
        <button class="btn-novo-pedido" id="btnNovoPedido">Fechar</button>
      </div>
    </div>
  </div>

  <footer class="footer">
    <div class="container">
      <p>&copy; <?= date('Y') ?> <?= NOME_LOJA ?>. Todos os direitos reservados.</p>
    </div>
  </footer>

  <script>const WHATSAPP_LOJA = '<?= WHATSAPP_NUMERO ?>';</script>
  <script src="script.js?v=<?= filemtime('script.js') ?>"></script>
</body>
</html>
