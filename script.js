let carrinho = [];
let pedidoAtualId = null;
let intervalAcompanhamento = null;
let pendenciaMeiaPizza = null;
let pendenciaTresPizza = null;
let pendenciaQuatroPizza = null;

const carrinhoBtn = document.getElementById("carrinhoBtn");
const carrinhoPanel = document.getElementById("carrinhoPanel");
const fecharCarrinho = document.getElementById("fecharCarrinho");
const carrinhoItems = document.getElementById("carrinhoItems");
const carrinhoCount = document.getElementById("carrinhoCount");
const carrinhoFooter = document.getElementById("carrinhoFooter");
const totalPreco = document.getElementById("totalPreco");
const overlay = document.getElementById("overlay");
const finalizarPedido = document.getElementById("finalizarPedido");
const observacaoPedido = document.getElementById("observacaoPedido");
const clienteNome = document.getElementById("clienteNome");
const clienteTelefone = document.getElementById("clienteTelefone");
const clienteEndereco = document.getElementById("clienteEndereco");
const pedidoModal = document.getElementById("pedidoModal");
const btnNovoPedido = document.getElementById("btnNovoPedido");
const meiaPizzaModal = document.getElementById("meiaPizzaModal");
const btnSimMeia = document.getElementById("btnSimMeia");
const btnNaoMeia = document.getElementById("btnNaoMeia");
const tresPizzaModal = document.getElementById("tresPizzaModal");
const btnSimTres = document.getElementById("btnSimTres");
const btnNaoTres = document.getElementById("btnNaoTres");
const quatroPizzaModal = document.getElementById("quatroPizzaModal");
const btnSimQuatro = document.getElementById("btnSimQuatro");
const btnNaoQuatro = document.getElementById("btnNaoQuatro");
const trocoModal = document.getElementById("trocoModal");
const btnTrocoSim = document.getElementById("btnTrocoSim");
const btnTrocoNao = document.getElementById("btnTrocoNao");
const btnTrocoConfirmar = document.getElementById("btnTrocoConfirmar");
const trocoValorBox = document.getElementById("trocoValorBox");
const trocoValorInput = document.getElementById("trocoValorInput");
const trocoCalculo = document.getElementById("trocoCalculo");
let pedidoPendente = null;

function adicionarAoCarrinho(id, nome, precoBase) {
  const card = document.querySelector(`.produto-card [onclick*="${id}"]`).closest('.produto-card');
  const tamanhoBtn = card.querySelector('.tamanho-btn.active');
  const tamanho = tamanhoBtn ? tamanhoBtn.dataset.tamanho : 'M';
  const preco = tamanhoBtn ? parseFloat(tamanhoBtn.dataset.preco) : precoBase;

  const itemExistente = carrinho.find(c => c.chave === `${id}-${tamanho}`);
  if (itemExistente) {
    itemExistente.qtd++;
    atualizarCarrinho();
    return;
  }

  if (tamanho === 'G') {
    const meiaG = carrinho.find(c => c.tamanho === 'G' && c.meia && c.meiaCount === 2);
    if (meiaG) {
      abrirTresPizzaModal(id, nome, preco, tamanho, [meiaG]);
      return;
    }

    const tresG = carrinho.find(c => c.tamanho === 'G' && c.meia && c.meiaCount === 3);
    if (tresG) {
      abrirQuatroPizzaModal(id, nome, preco, tamanho, [tresG]);
      return;
    }

    const singlesG = carrinho.filter(c => c.tamanho === 'G' && !c.meia);
    if (singlesG.length === 1) {
      abrirMeiaPizzaModal(id, nome, preco, tamanho, singlesG);
      return;
    } else if (singlesG.length === 2) {
      abrirTresPizzaModal(id, nome, preco, tamanho, singlesG);
      return;
    } else if (singlesG.length === 3) {
      abrirQuatroPizzaModal(id, nome, preco, tamanho, singlesG);
      return;
    }
  }

  carrinho.push({ id, nome, preco, tamanho, qtd: 1, chave: `${id}-${tamanho}` });
  atualizarCarrinho();
}

function extrairSabores(itens) {
  const sabores = [];
  itens.forEach(item => {
    if (item.sabores) {
      sabores.push(...item.sabores);
    } else {
      sabores.push({ id: item.id, nome: item.nome, preco: item.preco });
    }
  });
  return sabores;
}

function removerItens(itens) {
  carrinho = carrinho.filter(c => !itens.some(e => c.chave === e.chave));
}

function combinarSabores(itensExistentes, id, nome, preco, tamanho) {
  const sabores = [...extrairSabores(itensExistentes), { id, nome, preco }];
  const precoTotal = sabores.reduce((soma, s) => soma + s.preco, 0) / sabores.length;
  const totalSabores = sabores.length;
  const prefixo = totalSabores === 2 ? 'meia' : totalSabores === 3 ? 'tres' : 'quatro';
  const chaveId = sabores.map(s => s.id).join('-');
  const nomeFormatado = totalSabores === 2
    ? `Meia ${sabores.map(s => s.nome).join(' / ')}`
    : `${totalSabores} Sabores: ${sabores.map(s => s.nome).join(' / ')}`;

  removerItens(itensExistentes);

  carrinho.push({
    id: `${prefixo}-${chaveId}`,
    nome: nomeFormatado,
    preco: precoTotal,
    tamanho,
    qtd: 1,
    chave: `${prefixo}-${chaveId}-${tamanho}`,
    meia: true,
    meiaCount: totalSabores,
    ids: sabores.map(s => s.id),
    sabores
  });
}

function abrirMeiaPizzaModal(id, nome, preco, tamanho, itensExistentes) {
  pendenciaMeiaPizza = { id, nome, preco, tamanho, itensExistentes };
  const saborBase = itensExistentes[0].nome;
  document.getElementById('meiaSaborExistente').textContent = saborBase;
  document.getElementById('meiaSaborNovo').textContent = nome;
  document.getElementById('meiaSaborExistentePreview').textContent = saborBase;
  document.getElementById('meiaSaborNovoPreview').textContent = nome;
  meiaPizzaModal.classList.add('open');
  overlay.classList.add('active');
}

function fecharMeiaPizzaModal() {
  meiaPizzaModal.classList.remove('open');
  overlay.classList.remove('active');
}

function abrirTresPizzaModal(id, nome, preco, tamanho, itensExistentes) {
  pendenciaTresPizza = { id, nome, preco, tamanho, itensExistentes };
  const nomes = extrairSabores(itensExistentes).map(s => s.nome).join(', ');
  document.getElementById('tresSaboresExistentes').textContent = nomes;
  document.getElementById('tresSaborNovo').textContent = nome;
  document.getElementById('tresSaboresExistentesPreview').textContent = nomes;
  document.getElementById('tresSaborNovoPreview').textContent = nome;
  tresPizzaModal.classList.add('open');
  overlay.classList.add('active');
}

function fecharTresPizzaModal() {
  tresPizzaModal.classList.remove('open');
  overlay.classList.remove('active');
}

function abrirQuatroPizzaModal(id, nome, preco, tamanho, itensExistentes) {
  pendenciaQuatroPizza = { id, nome, preco, tamanho, itensExistentes };
  const nomes = extrairSabores(itensExistentes).map(s => s.nome).join(', ');
  document.getElementById('quatroSaboresExistentes').textContent = nomes;
  document.getElementById('quatroSaborNovo').textContent = nome;
  document.getElementById('quatroSaboresExistentesPreview').textContent = nomes;
  document.getElementById('quatroSaborNovoPreview').textContent = nome;
  quatroPizzaModal.classList.add('open');
  overlay.classList.add('active');
}

function fecharQuatroPizzaModal() {
  quatroPizzaModal.classList.remove('open');
  overlay.classList.remove('active');
}

btnSimMeia.addEventListener('click', () => {
  if (!pendenciaMeiaPizza) return;
  const { id, nome, preco, tamanho, itensExistentes } = pendenciaMeiaPizza;
  combinarSabores(itensExistentes, id, nome, preco, tamanho);
  pendenciaMeiaPizza = null;
  fecharMeiaPizzaModal();
  atualizarCarrinho();
});

btnNaoMeia.addEventListener('click', () => {
  if (!pendenciaMeiaPizza) return;
  const { id, nome, preco, tamanho } = pendenciaMeiaPizza;
  carrinho.push({ id, nome, preco, tamanho, qtd: 1, chave: `${id}-${tamanho}` });
  pendenciaMeiaPizza = null;
  fecharMeiaPizzaModal();
  atualizarCarrinho();
});

btnSimTres.addEventListener('click', () => {
  if (!pendenciaTresPizza) return;
  const { id, nome, preco, tamanho, itensExistentes } = pendenciaTresPizza;
  combinarSabores(itensExistentes, id, nome, preco, tamanho);
  pendenciaTresPizza = null;
  fecharTresPizzaModal();
  atualizarCarrinho();
});

btnNaoTres.addEventListener('click', () => {
  if (!pendenciaTresPizza) return;
  const { id, nome, preco, tamanho } = pendenciaTresPizza;
  carrinho.push({ id, nome, preco, tamanho, qtd: 1, chave: `${id}-${tamanho}` });
  pendenciaTresPizza = null;
  fecharTresPizzaModal();
  atualizarCarrinho();
});

btnSimQuatro.addEventListener('click', () => {
  if (!pendenciaQuatroPizza) return;
  const { id, nome, preco, tamanho, itensExistentes } = pendenciaQuatroPizza;
  combinarSabores(itensExistentes, id, nome, preco, tamanho);
  pendenciaQuatroPizza = null;
  fecharQuatroPizzaModal();
  atualizarCarrinho();
});

btnNaoQuatro.addEventListener('click', () => {
  if (!pendenciaQuatroPizza) return;
  const { id, nome, preco, tamanho } = pendenciaQuatroPizza;
  carrinho.push({ id, nome, preco, tamanho, qtd: 1, chave: `${id}-${tamanho}` });
  pendenciaQuatroPizza = null;
  fecharQuatroPizzaModal();
  atualizarCarrinho();
});

function selecionarTamanho(btn) {
  const card = btn.closest('.produto-card');
  card.querySelectorAll('.tamanho-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  const preco = parseFloat(btn.dataset.preco);
  const id = card.querySelector('.btn-adicionar').getAttribute('onclick').match(/\d+/)[0];
  const precoEl = card.querySelector('.produto-preco');
  if (precoEl) precoEl.textContent = `R$ ${preco.toFixed(2).replace('.', ',')}`;
}

function removerDoCarrinho(chave) {
  carrinho = carrinho.filter(c => c.chave !== chave);
  atualizarCarrinho();
}

function mudarQtd(chave, delta) {
  const item = carrinho.find(c => c.chave === chave);
  if (item) {
    item.qtd += delta;
    if (item.qtd <= 0) {
      removerDoCarrinho(chave);
    } else {
      atualizarCarrinho();
    }
  }
}

function atualizarCarrinho() {
  const totalItens = carrinho.reduce((soma, c) => soma + c.qtd, 0);
  carrinhoCount.textContent = totalItens;

  if (carrinho.length === 0) {
    carrinhoItems.innerHTML = '<p class="carrinho-vazio">Seu carrinho está vazio</p>';
    carrinhoFooter.style.display = "none";
    return;
  }

  carrinhoFooter.style.display = "block";

  carrinhoItems.innerHTML = carrinho.map(c => `
    <div class="carrinho-item">
      <div class="carrinho-item-info">
        <div class="carrinho-item-nome">${c.nome} <span class="carrinho-tamanho">${c.tamanho}</span></div>
        <div class="carrinho-item-preco">R$ ${(c.preco * c.qtd).toFixed(2).replace('.', ',')}</div>
      </div>
      <div class="carrinho-item-qtd">
        <button class="qtd-btn" onclick="mudarQtd('${c.chave}', -1)">-</button>
        <span>${c.qtd}</span>
        <button class="qtd-btn" onclick="mudarQtd('${c.chave}', 1)">+</button>
      </div>
    </div>
  `).join("");

  const total = carrinho.reduce((soma, c) => soma + c.preco * c.qtd, 0);
  totalPreco.textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
}

function abrirCarrinho() {
  carrinhoPanel.classList.add("open");
  overlay.classList.add("active");
}

function fecharCarrinhoPanel() {
  carrinhoPanel.classList.remove("open");
  overlay.classList.remove("active");
}

function finalizar() {
  if (carrinho.length === 0) {
    alert('Adicione itens ao carrinho!');
    return;
  }

  const nome = clienteNome.value.trim();
  const telefone = clienteTelefone.value.trim();
  const endereco = clienteEndereco.value.trim();

  if (!nome || !telefone || !endereco) {
    alert('Preencha nome, telefone e endereco!');
    return;
  }

  const total = carrinho.reduce((soma, c) => soma + c.preco * c.qtd, 0);

  const pagamentoEl = document.querySelector('input[name="pagamento"]:checked');
  const pagamento = pagamentoEl ? pagamentoEl.value : 'dinheiro';

  const dados = {
    nome: nome,
    telefone: telefone,
    endereco: endereco,
    observacoes: observacaoPedido.value.trim(),
    pagamento: pagamento,
    total: total,
    itens: carrinho.map(c => ({ id: c.id, nome: c.nome, qtd: c.qtd, preco: c.preco, tamanho: c.tamanho }))
  };

  if (pagamento === 'dinheiro') {
    pedidoPendente = dados;
    document.getElementById('trocoTotal').textContent = `R$ ${total.toFixed(2).replace('.', ',')}`;
    trocoValorInput.value = '';
    trocoValorBox.style.display = 'none';
    trocoCalculo.innerHTML = '';
    btnTrocoSim.style.display = '';
    btnTrocoNao.style.display = '';
    btnTrocoConfirmar.style.display = 'none';
    trocoModal.classList.add('open');
    overlay.classList.add('active');
    return;
  }

  enviarPedido(dados, null);
}

function abrirTrocoValor() {
  trocoValorBox.style.display = 'block';
  btnTrocoSim.style.display = 'none';
  btnTrocoConfirmar.style.display = '';
  calcularTroco();
  setTimeout(() => trocoValorInput.focus(), 50);
}

function calcularTroco() {
  if (!pedidoPendente) return;
  const total = pedidoPendente.total;
  const valorPago = parseFloat(trocoValorInput.value.replace(',', '.')) || 0;

  if (!trocoValorInput.value.trim()) {
    trocoCalculo.innerHTML = '';
    return;
  }

  if (valorPago < total) {
    trocoCalculo.innerHTML = `<div class="troco-erro">Valor menor que o total. Faltam R$ ${(total - valorPago).toFixed(2).replace('.', ',')}</div>`;
    return;
  }

  const troco = valorPago - total;
  trocoCalculo.innerHTML = `
    <div class="troco-pago">Valor recebido: R$ ${valorPago.toFixed(2).replace('.', ',')}</div>
    <div class="troco-devolver">Troco a devolver: R$ ${troco.toFixed(2).replace('.', ',')}</div>
  `;
}

function fecharTrocoModal() {
  trocoModal.classList.remove('open');
  overlay.classList.remove('active');
  pedidoPendente = null;
}

btnTrocoSim.addEventListener('click', abrirTrocoValor);
trocoValorInput.addEventListener('input', calcularTroco);

btnTrocoNao.addEventListener('click', () => {
  const dados = pedidoPendente;
  fecharTrocoModal();
  enviarPedido(dados, null);
});

btnTrocoConfirmar.addEventListener('click', () => {
  if (!pedidoPendente) return;
  const total = pedidoPendente.total;
  const valorPago = parseFloat(trocoValorInput.value.replace(',', '.')) || 0;
  if (!valorPago || valorPago < total) {
    alert('Informe um valor suficiente para pagar o pedido.');
    return;
  }
  const dados = pedidoPendente;
  fecharTrocoModal();
  enviarPedido(dados, valorPago);
});

function enviarPedido(pedido, troco) {
  const dados = { ...pedido, troco };

  finalizarPedido.disabled = true;
  finalizarPedido.textContent = 'Enviando...';

  let janelaWhats = window.open('', '_blank');
  if (!janelaWhats) {
    janelaWhats = null;
  }

  fetch('api/pedidos.php', {
    method: 'POST',
    headers: { 'Content-Type': 'application/json' },
    body: JSON.stringify(dados)
  }).then(r => r.json()).then(d => {
    if (d.sucesso) {
      pedidoAtualId = d.pedido_id;
      const linkWhats = montarLinkWhats(d.pedido_id, pedido.nome, pedido.telefone);
      if (janelaWhats) {
        janelaWhats.location.href = linkWhats;
      } else {
        window.open(linkWhats, '_blank');
      }
      mostrarPedidoRealizado(d.pedido_id, pedido.total);
    } else {
      if (janelaWhats) janelaWhats.close();
      alert('Erro ao salvar pedido. Tente novamente.');
      finalizarPedido.disabled = false;
      finalizarPedido.textContent = 'Finalizar Pedido';
    }
  }).catch(() => {
    if (janelaWhats) janelaWhats.close();
    pedidoAtualId = 'temp-' + Date.now();
    mostrarPedidoRealizado(pedidoAtualId, pedido.total);
  });
}

function montarLinkWhats(pedidoId, nomeCliente, telefoneCliente) {
  const baseUrl = window.location.origin + window.location.pathname.replace('index.php', '');
  const linkRastreio = baseUrl + 'rastreio.php?id=' + pedidoId;
  const tel = (telefoneCliente || '').replace(/\D/g, '');
  return 'https://wa.me/' + tel + '?text=' + encodeURIComponent(linkRastreio);
}

function mostrarPedidoRealizado(pedidoId, total) {
  document.getElementById('pedidoNumero').textContent = pedidoId;

  let resumoHtml = '<h4>Resumo do Pedido</h4>';
  carrinho.forEach(c => {
    resumoHtml += `<div class="resumo-item"><span>${c.qtd}x ${c.nome} (${c.tamanho})</span><span>R$ ${(c.preco * c.qtd).toFixed(2).replace('.', ',')}</span></div>`;
  });
  resumoHtml += `<div class="resumo-total"><span>Total</span><span>R$ ${total.toFixed(2).replace('.', ',')}</span></div>`;
  document.getElementById('pedidoResumo').innerHTML = resumoHtml;

  document.querySelectorAll('.timeline-item').forEach(item => item.classList.remove('active', 'done'));
  document.getElementById('step-recebido').classList.add('active', 'done');

  fecharCarrinhoPanel();
  pedidoModal.classList.add('open');
  overlay.classList.add('active');

  carrinho = [];
  atualizarCarrinho();
  clienteNome.value = '';
  clienteTelefone.value = '';
  clienteEndereco.value = '';
  clienteEndereco.value = '';
  observacaoPedido.value = '';
  document.querySelector('input[name="pagamento"][value="dinheiro"]').checked = true;
  finalizarPedido.disabled = false;
  finalizarPedido.textContent = 'Finalizar Pedido';

  if (pedidoAtualId && typeof pedidoAtualId === 'number') {
    iniciarAcompanhamento(pedidoAtualId);
  }
}

function iniciarAcompanhamento(pedidoId) {
  if (intervalAcompanhamento) clearInterval(intervalAcompanhamento);

  intervalAcompanhamento = setInterval(() => {
    fetch('api/status.php?id=' + pedidoId)
      .then(r => r.json())
      .then(d => {
        if (d.status) {
          atualizarTimeline(d.status);
          if (d.status === 'entregue') {
            clearInterval(intervalAcompanhamento);
          }
        }
      })
      .catch(() => {});
  }, 5000);
}

function atualizarTimeline(status) {
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
    el.classList.remove('active', 'done');
    if (index < currentIndex) {
      el.classList.add('done');
    } else if (index === currentIndex) {
      el.classList.add('active', 'done');
    }
  });
}

function fecharPedidoModal() {
  pedidoModal.classList.remove('open');
  overlay.classList.remove('active');
  if (intervalAcompanhamento) clearInterval(intervalAcompanhamento);
}

// Eventos
carrinhoBtn.addEventListener("click", abrirCarrinho);
fecharCarrinho.addEventListener("click", fecharCarrinhoPanel);
overlay.addEventListener("click", () => {
  fecharCarrinhoPanel();
  fecharPedidoModal();
  fecharMeiaPizzaModal();
  fecharTresPizzaModal();
  fecharQuatroPizzaModal();
  fecharTrocoModal();
});
const buscaInput = document.getElementById('buscaProduto');
const buscaClear = document.getElementById('buscaClear');
let buscaTermo = '';
let categoriaAtiva = 'todos';

function aplicarFiltros() {
  const termo = buscaTermo.toLowerCase().trim();

  document.querySelectorAll(".produto-card").forEach(card => {
    const nome = card.querySelector('.produto-nome').textContent.toLowerCase();
    const desc = card.querySelector('.produto-desc').textContent.toLowerCase();
    const catOk = categoriaAtiva === "todos" || card.dataset.categoria === categoriaAtiva;
    const buscaOk = !termo || nome.includes(termo) || desc.includes(termo);
    card.style.display = (catOk && buscaOk) ? "" : "none";
  });
}

buscaInput.addEventListener("input", () => {
  buscaTermo = buscaInput.value;
  buscaClear.style.display = buscaTermo ? "block" : "none";
  aplicarFiltros();
});

buscaClear.addEventListener("click", () => {
  buscaInput.value = '';
  buscaTermo = '';
  buscaClear.style.display = "none";
  aplicarFiltros();
});

document.querySelectorAll(".cat-btn").forEach(btn => {
  btn.addEventListener("click", () => {
    document.querySelectorAll(".cat-btn").forEach(b => b.classList.remove("active"));
    btn.classList.add("active");
    categoriaAtiva = btn.dataset.categoria;
    aplicarFiltros();
  });
});

finalizarPedido.addEventListener("click", finalizar);
btnNovoPedido.addEventListener("click", fecharPedidoModal);
