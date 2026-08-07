<?php
require_once 'config.php';
$pdo = conexao();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$pedido = null;
if ($id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id = ?");
    $stmt->execute([$id]);
    $pedido = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= NOME_LOJA ?> - Acompanhar Pedido</title>
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
          <span class="tagline">Acompanhe seu pedido</span>
        </div>
      </div>
      <div class="header-actions">
        <a href="index.php" class="btn-voltar-loja">&#8592; Voltar à Loja</a>
      </div>
    </div>
  </header>

  <main class="container main-content">
    <?php if (!$pedido): ?>
      <div class="rastreio-card">
        <div class="rastreio-erro">
          <h2>&#128533; Pedido não encontrado</h2>
          <p>Verifique o link ou o número do pedido e tente novamente.</p>
          <a href="index.php" class="btn-rastreio">Fazer um pedido</a>
        </div>
      </div>
    <?php else: ?>
      <div class="rastreio-card">
        <div class="rastreio-header">
          <h2>&#127829; Acompanhamento do Pedido</h2>
          <p class="rastreio-numero">Pedido <strong>#<?= $pedido['id'] ?></strong></p>
          <p class="rastreio-cliente">Cliente: <strong><?= htmlspecialchars($pedido['cliente_nome']) ?></strong></p>
          <p class="rastreio-total">Total: <strong>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></strong></p>
          <div class="rastreio-status" id="rastreioStatus">
            <span class="status-badge"><?= htmlspecialchars($pedido['status']) ?></span>
          </div>
        </div>

        <div class="timeline rastreio-timeline" data-status="<?= htmlspecialchars($pedido['status']) ?>">
          <div class="timeline-item" id="step-recebido">
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
              <span>Sua pizza está sendo feita</span>
            </div>
          </div>
          <div class="timeline-item" id="step-entrega">
            <div class="timeline-dot"></div>
            <div class="timeline-content">
              <strong>Saiu para Entrega</strong>
              <span>A caminho do seu endereço</span>
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
    <?php endif; ?>
  </main>

  <footer class="footer">
    <div class="container">
      <p>&copy; <?= date('Y') ?> <?= NOME_LOJA ?>. Todos os direitos reservados.</p>
    </div>
  </footer>

  <?php if ($pedido): ?>
  <script>
    const pedidoId = <?= $pedido['id'] ?>;
    const statusAtual = <?= json_encode($pedido['status']) ?>;

    function atualizarStatus(status) {
      const badge = document.getElementById('rastreioStatus');
      if (badge) {
        const nomes = {
          'pendente': 'Pendente',
          'preparando': 'Preparando',
          'saiu_entrega': 'Saiu p/ Entrega',
          'entregue': 'Entregue',
          'cancelado': 'Cancelado'
        };
        badge.innerHTML = '<span class="status-badge status-' + status + '">' + (nomes[status] || status) + '</span>';
      }

      const steps = ['recebido', 'preparando', 'entrega', 'entregue'];
      const statusMap = {
        'pendente': 'recebido',
        'preparando': 'preparando',
        'saiu_entrega': 'entrega',
        'entregue': 'entregue',
        'cancelado': 'recebido'
      };

      const currentStep = statusMap[status] || 'recebido';
      const currentIndex = steps.indexOf(currentStep);

      steps.forEach((step, index) => {
        const el = document.getElementById('step-' + step);
        if (!el) return;
        el.classList.remove('active', 'done');
        if (index < currentIndex) {
          el.classList.add('done');
        } else if (index === currentIndex) {
          el.classList.add('active', 'done');
        }
      });
    }

    function buscarStatus() {
      fetch('api/status.php?id=' + pedidoId)
        .then(r => r.json())
        .then(d => {
          if (d.status) {
            atualizarStatus(d.status);
            if (d.status === 'entregue' || d.status === 'cancelado') {
              clearInterval(intervalo);
            }
          }
        })
        .catch(() => {});
    }

    atualizarStatus(statusAtual);
    const intervalo = setInterval(buscarStatus, 5000);
  </script>
  <?php endif; ?>
</body>
</html>
