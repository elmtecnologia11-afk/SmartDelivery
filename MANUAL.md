<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Manual SmartDelivery - Como Usar o Sistema</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Inter', sans-serif; background: #f8f9fa; color: #1a1a2e; line-height: 1.6; }
    
    .hero { background: linear-gradient(135deg, #1a1a2e 0%, #c41e1e 100%); color: #fff; padding: 60px 20px; text-align: center; }
    .hero h1 { font-size: 2.5rem; margin-bottom: 10px; }
    .hero p { font-size: 1.1rem; opacity: 0.9; }
    
    .container { max-width: 900px; margin: 0 auto; padding: 40px 20px; }
    
    .menu { background: #fff; border-radius: 12px; padding: 20px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); position: sticky; top: 20px; z-index: 10; }
    .menu h3 { margin-bottom: 12px; color: #c41e1e; }
    .menu ul { list-style: none; display: flex; flex-wrap: wrap; gap: 8px; }
    .menu a { background: #f0f0f0; padding: 8px 16px; border-radius: 20px; text-decoration: none; color: #333; font-size: 0.9rem; transition: all 0.2s; }
    .menu a:hover { background: #c41e1e; color: #fff; }
    
    .secao { background: #fff; border-radius: 12px; padding: 30px; margin-bottom: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
    .secao h2 { color: #c41e1e; font-size: 1.5rem; margin-bottom: 20px; padding-bottom: 10px; border-bottom: 2px solid #f0f0f0; }
    .secao h3 { color: #1a1a2e; font-size: 1.2rem; margin: 20px 0 10px; }
    
    .passo { display: flex; gap: 20px; margin-bottom: 25px; align-items: flex-start; }
    .passo-numero { background: #c41e1e; color: #fff; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; flex-shrink: 0; }
    .passo-conteudo { flex: 1; }
    .passo-conteudo h4 { margin-bottom: 5px; color: #1a1a2e; }
    .passo-conteudo p { color: #555; font-size: 0.95rem; }
    
    .imagem-placeholder { background: #f0f0f0; border: 2px dashed #ccc; border-radius: 10px; padding: 30px; text-align: center; margin: 15px 0; color: #888; }
    .imagem-placeholder .icone { font-size: 2rem; margin-bottom: 10px; }
    
    .dica { background: #e8f5e9; border-left: 4px solid #27ae60; padding: 15px; border-radius: 0 8px 8px 0; margin: 15px 0; }
    .dica strong { color: #27ae60; }
    
    .aviso { background: #fff3e0; border-left: 4px solid #ff9800; padding: 15px; border-radius: 0 8px 8px 0; margin: 15px 0; }
    .aviso strong { color: #ff9800; }
    
    .tabela { width: 100%; border-collapse: collapse; margin: 15px 0; }
    .tabela th, .tabela td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; }
    .tabela th { background: #1a1a2e; color: #fff; font-size: 0.85rem; }
    .tabela td { font-size: 0.9rem; }
    .tabela tr:hover { background: #f8f8f8; }
    
    .status-badge { padding: 4px 12px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; color: #fff; display: inline-block; }
    .status-pendente { background: #f39c12; }
    .status-preparando { background: #3498db; }
    .status-saiu_entrega { background: #9b59b6; }
    .status-entregue { background: #27ae60; }
    .status-cancelado { background: #e74c3c; }
    
    .fluxo { display: flex; align-items: center; gap: 10px; margin: 20px 0; flex-wrap: wrap; }
    .fluxo-item { background: #f0f0f0; padding: 10px 20px; border-radius: 8px; font-weight: 600; font-size: 0.9rem; }
    .fluxo-seta { color: #c41e1e; font-size: 1.2rem; }
    
    .funcionalidade { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin: 20px 0; }
    .func-card { background: #f8f9fa; padding: 20px; border-radius: 10px; border: 1px solid #e0e0e0; }
    .func-card h4 { color: #c41e1e; margin-bottom: 8px; }
    .func-card p { font-size: 0.9rem; color: #555; }
    
    .url-box { background: #1a1a2e; color: #fff; padding: 12px 20px; border-radius: 8px; font-family: monospace; margin: 10px 0; word-break: break-all; }
    
    @media (max-width: 768px) {
      .hero h1 { font-size: 1.8rem; }
      .passo { flex-direction: column; gap: 10px; }
      .fluxo { flex-direction: column; align-items: stretch; }
      .fluxo-seta { transform: rotate(90deg); text-align: center; }
    }
  </style>
</head>
<body>

  <div class="hero">
    <h1>Manual SmartDelivery</h1>
    <p>Guia completo para usar o sistema de delivery</p>
  </div>

  <div class="container">

    <!-- MENU DE NAVEGAÇÃO -->
    <div class="menu">
      <h3>Sumário</h3>
      <ul>
        <li><a href="#visao-geral">Visão Geral</a></li>
        <li><a href="#acesso">Como Acessar</a></li>
        <li><a href="#cliente">Fazer Pedido (Cliente)</a></li>
        <li><a href="#carrinho">Carrinho e Checkout</a></li>
        <li><a href="#rastreio">Acompanhar Pedido</a></li>
        <li><a href="#admin">Painel Administrativo</a></li>
        <li><a href="#pedidos-admin">Gerenciar Pedidos</a></li>
        <li><a href="#produtos">Cadastrar Produtos</a></li>
        <li><a href="#configuracoes">Configurações</a></li>
      </ul>
    </div>

    <!-- VISÃO GERAL -->
    <div class="secao" id="visao-geral">
      <h2>📋 Visão Geral do Sistema</h2>
      <p>O SmartDelivery é um sistema completo para delivery de pizzas (ou outros produtos). Ele possui três partes principais:</p>
      
      <div class="funcionalidade">
        <div class="func-card">
          <h4>🛒 Loja Online</h4>
          <p>Onde o cliente faz seu pedido, escolhe tamanhos, sabores e finaliza a compra.</p>
        </div>
        <div class="func-card">
          <h4>📱 Acompanhamento</h4>
          <p>Página onde o cliente acompanha o status do pedido em tempo real.</p>
        </div>
        <div class="func-card">
          <h4>⚙️ Painel Admin</h4>
          <p>O restaurante gerencia pedidos, muda status e cadastra produtos.</p>
        </div>
      </div>

      <h3>Fluxo do Sistema</h3>
      <div class="fluxo">
        <div class="fluxo-item">Cliente faz pedido</div>
        <span class="fluxo-seta">→</span>
        <div class="fluxo-item">Pedido salvo no sistema</div>
        <span class="fluxo-seta">→</span>
        <div class="fluxo-item">Admin vê o pedido</div>
        <span class="fluxo-seta">→</span>
        <div class="fluxo-item">Prepara o pedido</div>
        <span class="fluxo-seta">→</span>
        <div class="fluxo-item">Muda status</div>
        <span class="fluxo-seta">→</span>
        <div class="fluxo-item">Cliente acompanha</div>
        <span class="fluxo-seta">→</span>
        <div class="fluxo-item">Entregue!</div>
      </div>
    </div>

    <!-- COMO ACESSAR -->
    <div class="secao" id="acesso">
      <h2>🌐 Como Acessar o Sistema</h2>
      
      <h3>Página Principal (Loja)</h3>
      <div class="url-box">https://seu-app.up.railway.app/</div>
      <p>É a página que os clientes acessam para fazer pedidos.</p>

      <h3>Painel Administrativo</h3>
      <div class="url-box">https://seu-app.up.railway.app/admin/</div>
      <p>Onde o restaurante gerencia pedidos e produtos.</p>

      <h3>Acompanhamento de Pedido</h3>
      <div class="url-box">https://seu-app.up.railway.app/rastreio.php?id=NUMERO_PEDIDO</div>
      <p>Link enviado ao cliente via WhatsApp para acompanhar o pedido.</p>

      <div class="dica">
        <strong>Dica:</strong> Substitua "seu-app.up.railway.app" pelo endereço que o Railway gerar para seu projeto.
      </div>
    </div>

    <!-- FLUXO DO CLIENTE -->
    <div class="secao" id="cliente">
      <h2>🛒 Como o Cliente Faz um Pedido</h2>
      
      <div class="passo">
        <div class="passo-numero">1</div>
        <div class="passo-conteudo">
          <h4>Abrir a Loja</h4>
          <p>O cliente acessa o link da loja pelo celular ou computador. Aparecerá a página principal com todos os produtos organizados por categorias.</p>
          <div class="imagem-placeholder">
            <div class="icone">🏪</div>
            <p><strong>Tela Principal</strong><br>
            Cabeçalho com nome da loja, horário e botão do carrinho.<br>
            Barra de busca para encontrar produtos.<br>
            Botões de categorias: Todos, Tradicionais, Especiais, Doces, Bebidas.</p>
          </div>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">2</div>
        <div class="passo-conteudo">
          <h4>Escolher o Produto</h4>
          <p>O cliente navega pelos produtos e escolhe o que quer. Para cada produto, pode selecionar o tamanho (P, M ou G) e ver o preço atualizado.</p>
          <div class="imagem-placeholder">
            <div class="icone">🍕</div>
            <p><strong>Card do Produto</strong><br>
            Imagem do produto à esquerda.<br>
            Nome e descrição.<br>
            Botões de tamanho: P (Pequeno), M (Médio), G (Grande).<br>
            Preço exibido abaixo.<br>
            Botão "Adicionar" para colocar no carrinho.</p>
          </div>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">3</div>
        <div class="passo-conteudo">
          <h4>Selecionar o Tamanho</h4>
          <p>Ao clicar em um tamanho, o preço muda automaticamente. O tamanho selecionado fica destacado em vermelho.</p>
          <div class="imagem-placeholder">
            <div class="icone">📏</div>
            <p><strong>Exemplo:</strong><br>
            Pizza Margherita: P = R$ 31,92 | M = R$ 39,90 | G = R$ 51,87<br>
            Ao clicar em "G", o preço muda para R$ 51,87</p>
          </div>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">4</div>
        <div class="passo-conteudo">
          <h4>Adicionar ao Carrinho</h4>
          <p>Ao clicar em "Adicionar", o produto entra no carrinho. O ícone do carrinho no canto superior direito mostra a quantidade de itens.</p>
          <div class="imagem-placeholder">
            <div class="icone">🛒</div>
            <p><strong>Carrinho:</strong> Mostra "2" quando dois itens foram adicionados.</p>
          </div>
        </div>
      </div>

      <div class="dica">
        <strong>Dica - Meia Pizza:</strong> Se o cliente já tem uma pizza grande (G) no carrinho e adicionar outra pizza grande, o sistema pergunta se quer dividir (meia pizza). Isso funciona para 2, 3 ou 4 sabores!
      </div>
    </div>

    <!-- CARRINHO E CHECKOUT -->
    <div class="secao" id="carrinho">
      <h2>💳 Carrinho e Finalização do Pedido</h2>
      
      <div class="passo">
        <div class="passo-numero">1</div>
        <div class="passo-conteudo">
          <h4>Abrir o Carrinho</h4>
          <p>Ao clicar no ícone do carrinho, abre-se o painel lateral com todos os itens adicionados.</p>
          <div class="imagem-placeholder">
            <div class="icone">📦</div>
            <p><strong>Painel do Carrinho:</strong><br>
            Lista de itens com nome, tamanho e preço.<br>
            Botões +/- para aumentar ou diminuir quantidade.<br>
            Preço total na parte inferior.</p>
          </div>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">2</div>
        <div class="passo-conteudo">
          <h4>Preencher Dados Pessoais</h4>
          <p>Antes de finalizar, o cliente precisa preencher:</p>
          <ul style="margin-left: 20px; margin-top: 8px; color: #555;">
            <li><strong>Seu nome</strong> - Nome completo</li>
            <li><strong>WhatsApp</strong> - Número com DDD (ex: 11999998888)</li>
            <li><strong>Endereço completo</strong> - Rua, número, bairro, complemento</li>
            <li><strong>Observações</strong> - Ex: "sem cebola", "borda recheada"</li>
          </ul>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">3</div>
        <div class="passo-conteudo">
          <h4>Escolher Forma de Pagamento</h4>
          <p>O cliente escolhe como vai pagar:</p>
          <div class="imagem-placeholder">
            <div class="icone">💰</div>
            <p><strong>Opções de Pagamento:</strong><br>
            💵 Dinheiro<br>
            💳 Cartão Crédito<br>
            💳 Cartão Débito<br>
            📱 Pix<br>
            🏛️ Vale Refeição</p>
          </div>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">4</div>
        <div class="passo-conteudo">
          <h4>Finalizar o Pedido</h4>
          <p>Ao clicar em "Finalizar Pedido":</p>
          <ul style="margin-left: 20px; margin-top: 8px; color: #555;">
            <li>Se pagamento for <strong>dinheiro</strong>, abre um popup perguntando se precisa de troco</li>
            <li>Se precisar de troco, informa o valor que vai pagar</li>
            <li>O sistema calcula automaticamente o troco</li>
            <li>O pedido é salvo e o link de acompanhamento é enviado via WhatsApp</li>
          </ul>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">5</div>
        <div class="passo-conteudo">
          <h4>Pedido Confirmado!</h4>
          <p>Após finalizar, aparece uma tela de confirmação com:</p>
          <div class="imagem-placeholder">
            <div class="icone">✅</div>
            <p><strong>Tela de Confirmação:</strong><br>
            ✓ Pedido Realizado!<br>
            Número do pedido (ex: #42)<br>
            Resumo com todos os itens e total<br>
            Timeline mostrando: Pedido Recebido → Preparando → Saiu para Entrega → Entregue<br>
            Link de acompanhamento enviado para o WhatsApp</p>
          </div>
        </div>
      </div>
    </div>

    <!-- ACOMPANHAMENTO -->
    <div class="secao" id="rastreio">
      <h2>📱 Como o Cliente Acompanha o Pedido</h2>
      
      <div class="passo">
        <div class="passo-numero">1</div>
        <div class="passo-conteudo">
          <h4>Receber o Link</h4>
          <p>Após o pedido, o cliente recebe um link via WhatsApp. Ao clicar, abre a página de acompanhamento.</p>
          <div class="url-box">https://seu-app.up.railway.app/rastreio.php?id=42</div>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">2</div>
        <div class="passo-conteudo">
          <h4>Visualizar o Status</h4>
          <p>A página mostra o status atual do pedido em tempo real. A cada 5 segundos, o sistema verifica se houve atualização.</p>
          <div class="imagem-placeholder">
            <div class="icone">📊</div>
            <p><strong>Timeline de Acompanhamento:</strong><br><br>
            ● Pedido Recebido (amarelo) - Aguardando preparo<br>
            ● Preparando (azul) - Sua pizza está sendo feita<br>
            ● Saiu para Entrega (roxo) - A caminho do seu endereço<br>
            ● Entregue (verde) - Bom apetite!</p>
          </div>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">3</div>
        <div class="passo-conteudo">
          <h4>Status em Tempo Real</h4>
          <p>O cliente não precisa atualizar a página. O status muda automaticamente quando o restaurante atualiza no painel admin.</p>
        </div>
      </div>

      <table class="tabela">
        <thead>
          <tr>
            <th>Status</th>
            <th>Significado</th>
            <th>Cor</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><span class="status-badge status-pendente">Pendente</span></td>
            <td>Pedido recebido, aguardando preparo</td>
            <td>Amarelo</td>
          </tr>
          <tr>
            <td><span class="status-badge status-preparando">Preparando</span></td>
            <td>Pizza sendo feita</td>
            <td>Azul</td>
          </tr>
          <tr>
            <td><span class="status-badge status-saiu_entrega">Saiu p/ Entrega</span></td>
            <td>Saiu do restaurante a caminho</td>
            <td>Roxo</td>
          </tr>
          <tr>
            <td><span class="status-badge status-entregue">Entregue</span></td>
            <td>Pedido entregue ao cliente</td>
            <td>Verde</td>
          </tr>
          <tr>
            <td><span class="status-badge status-cancelado">Cancelado</span></td>
            <td>Pedido cancelado (não pode ser alterado)</td>
            <td>Vermelho</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- PAINEL ADMIN -->
    <div class="secao" id="admin">
      <h2>⚙️ Painel Administrativo</h2>
      
      <div class="passo">
        <div class="passo-numero">1</div>
        <div class="passo-conteudo">
          <h4>Acessar o Admin</h4>
          <p>O restaurante acessa a página do admin pelo link:</p>
          <div class="url-box">https://seu-app.up.railway.app/admin/</div>
          <p>O painel tem duas abas principais: <strong>Pedidos</strong> e <strong>Produtos</strong>.</p>
        </div>
      </div>

      <div class="imagem-placeholder">
        <div class="icone">🖥️</div>
        <p><strong>Estrutura do Painel Admin:</strong><br><br>
        Cabeçalho escuro com nome da loja e botão "Voltar à Loja"<br>
        Abas: Pedidos | Produtos<br><br>
        <strong>Aba Pedidos:</strong> Lista de todos os pedidos com status, itens, pagamento e botões para mudar status<br>
        <strong>Aba Produtos:</strong> Tabela com todos os produtos, opções de editar e excluir, botão "+ Novo Produto"</p>
      </div>
    </div>

    <!-- GERENCIAR PEDIDOS -->
    <div class="secao" id="pedidos-admin">
      <h2>📦 Gerenciar Pedidos (Admin)</h2>
      
      <div class="passo">
        <div class="passo-numero">1</div>
        <div class="passo-conteudo">
          <h4>Visualizar Pedidos</h4>
          <p>Na aba "Pedidos", todos os pedidos aparecem do mais recente para o mais antigo. Cada pedido mostra:</p>
          <ul style="margin-left: 20px; margin-top: 8px; color: #555;">
            <li><strong>#Número</strong> - ID do pedido</li>
            <li><strong>Nome do cliente</strong></li>
            <li><strong>Status atual</strong> (com cor)</li>
            <li><strong>Telefone e endereço</strong></li>
            <li><strong>Itens pedidos</strong> (ex: "Margherita (G) x1, Calabresa (M) x2")</li>
            <li><strong>Observações</strong> do cliente</li>
            <li><strong>Forma de pagamento</strong> e troco (se aplicável)</li>
            <li><strong>Total</strong> do pedido</li>
          </ul>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">2</div>
        <div class="passo-conteudo">
          <h4>Mudar o Status do Pedido</h4>
          <p>Para cada pedido, há 5 botões de status. Clique no status adequado para atualizar:</p>
          <div class="imagem-placeholder">
            <div class="icone">🔄</div>
            <p><strong>Botões de Status:</strong><br><br>
            <span class="status-badge status-pendente">Pendente</span> - Quando o pedido chega<br>
            <span class="status-badge status-preparando">Preparando</span> - Quando começa a fazer<br>
            <span class="status-badge status-saiu_entrega">Saiu p/ Entrega</span> - Saiu do restaurante<br>
            <span class="status-badge status-entregue">Entregue</span> - Cliente recebeu<br>
            <span class="status-badge status-cancelado">Cancelado</span> - Cancelar o pedido</p>
          </div>
          <div class="aviso">
            <strong>Atenção:</strong> Um pedido cancelado NÃO pode ter o status alterado novamente!
          </div>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">3</div>
        <div class="passo-conteudo">
          <h4>Enviar Link de Acompanhamento</h4>
          <p>Cada pedido tem um botão verde "Enviar Link". Ao clicar, abre o WhatsApp com o link de acompanhamento pronto para enviar ao cliente.</p>
          <div class="imagem-placeholder">
            <div class="icone">💬</div>
            <p><strong>Botão:</strong> 📧 Enviar Link<br>
            Abre WhatsApp com mensagem: link de rastreio do pedido</p>
          </div>
        </div>
      </div>
    </div>

    <!-- CADASTRAR PRODUTOS -->
    <div class="secao" id="produtos">
      <h2>📝 Cadastrar e Gerenciar Produtos</h2>
      
      <div class="passo">
        <div class="passo-numero">1</div>
        <div class="passo-conteudo">
          <h4>Aba de Produtos</h4>
          <p>Clique na aba "Produtos" no painel admin. Aparecerá uma tabela com todos os produtos cadastrados.</p>
          <div class="imagem-placeholder">
            <div class="icone">📋</div>
            <p><strong>Tabela de Produtos:</strong><br><br>
            Colunas: Imagem | ID | Nome | Categoria | Preço P | Preço M | Preço G | Ações<br>
            Botões de Ação: Editar (azul) | Excluir (vermelho)</p>
          </div>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">2</div>
        <div class="passo-conteudo">
          <h4>Criar Novo Produto</h4>
          <p>Clique no botão "+ Novo Produto". Abre um formulário com os campos:</p>
          <div class="imagem-placeholder">
            <div class="icone">➕</div>
            <p><strong>Formulário de Novo Produto:</strong><br><br>
            <strong>Nome do Produto:</strong> Ex: Pizza Margherita<br>
            <strong>Descrição:</strong> Molho de tomate, muçarela, manjericão<br>
            <strong>Preços por Tamanho:</strong><br>
            - P (Pequeno): R$ ___<br>
            - M (Médio): R$ ___<br>
            - G (Grande): R$ ___<br>
            <strong>Categoria:</strong> Tradicionais, Especiais, Doces ou Bebidas<br>
            <strong>Imagem:</strong> Botão "Anexar" para selecionar foto do produto</p>
          </div>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">3</div>
        <div class="passo-conteudo">
          <h4>Editar um Produto</h4>
          <p>Na tabela de produtos, clique no botão azul "Editar" ao lado do produto. O mesmo formulário abre com os dados preenchidos. Altere o que precisar e clique "Salvar".</p>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">4</div>
        <div class="passo-conteudo">
          <h4>Excluir um Produto</h4>
          <p>Clique no botão vermelho "Excluir". Uma mensagem de confirmação aparece. Confirme para desativar o produto (ele não aparece mais na loja, mas não é deletado do banco).</p>
          <div class="aviso">
            <strong>Atenção:</strong> O produto é desativado, não deletado. Isso preserva o histórico de pedidos anteriores.
          </div>
        </div>
      </div>

      <div class="passo">
        <div class="passo-numero">5</div>
        <div class="passo-conteudo">
          <h4>Adicionar Imagem</h4>
          <p>No formulário do produto, clique em "Anexar" ou "Selecionar imagem". Escolha uma foto do produto no seu computador. A imagem aparece como pré-visualização antes de salvar.</p>
          <div class="dica">
            <strong>Dica:</strong> Use imagens com proporção 2:1 (largura x altura) para melhor visualização. Tamanho recomendado: 800x400 pixels.
          </div>
        </div>
      </div>
    </div>

    <!-- CONFIGURAÇÕES -->
    <div class="secao" id="configuracoes">
      <h2>🔧 Configurações do Sistema</h2>
      
      <h3>Arquivo de Configuração</h3>
      <p>As configurações ficam no arquivo <code>config.php</code>. Você pode alterar:</p>
      
      <table class="tabela">
        <thead>
          <tr>
            <th>Variável</th>
            <th>Descrição</th>
            <th>Exemplo</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><code>NOME_LOJA</code></td>
            <td>Nome que aparece no cabeçalho</td>
            <td>"Minha Pizzaria"</td>
          </tr>
          <tr>
            <td><code>WHATSAPP_NUMERO</code></td>
            <td>Número do WhatsApp da loja (com código do país)</td>
            <td>"5511999998888"</td>
          </tr>
          <tr>
            <td><code>HORARIO_FUNCIONAMENTO</code></td>
            <td>Horário exibido no cabeçalho</td>
            <td>"Seg-Dom: 18h - 23h"</td>
          </tr>
        </tbody>
      </table>

      <h3>Categorias Padrão</h3>
      <p>O sistema vem com 4 categorias pré-cadastradas:</p>
      <ul style="margin-left: 20px; margin-top: 8px; color: #555;">
        <li><strong>Tradicionais</strong> - Pizzas clássicas</li>
        <li><strong>Especiais</strong> - Pizzas diferenciadas</li>
        <li><strong>Doces</strong> - Pizzas doces</li>
        <li><strong>Bebidas</strong> - Refrigerantes, sucos</li>
      </ul>

      <h3>Produtos de Exemplo</h3>
      <p>O sistema já vem com produtos de exemplo para teste. Depois de configurar, exclua-os e cadastre seus próprios produtos.</p>

      <div class="dica">
        <strong>Dica de Segurança:</strong> Não compartilhe o link do painel admin publicamente. O admin não tem sistema de login (por simplicidade), então mantenha o endereço secreto.
      </div>
    </div>

    <!-- DICAS FINAIS -->
    <div class="secao">
      <h2>💡 Dicas Importantes</h2>
      
      <div class="funcionalidade">
        <div class="func-card">
          <h4>📱 Teste no Celular</h4>
          <p>O sistema é responsivo. Teste fazendo um pedido pelo celular para ver como o cliente vai experimentar.</p>
        </div>
        <div class="func-card">
          <h4>🔄 Atualização em Tempo Real</h4>
          <p>O status do pedido atualiza a cada 5 segundos tanto no admin quanto no acompanhamento do cliente.</p>
        </div>
        <div class="func-card">
          <h4>🍕 Meia Pizza</h4>
          <p>Para pizzas grandes (G), o sistema oferece dividir em 2, 3 ou 4 sabores automaticamente!</p>
        </div>
        <div class="func-card">
          <h4>💬 WhatsApp</h4>
          <p>O link de acompanhamento é enviado direto pelo WhatsApp para o cliente.</p>
        </div>
      </div>
    </div>

  </div>

  <footer style="background: #1a1a2e; color: #fff; text-align: center; padding: 20px; margin-top: 40px;">
    <p>SmartDelivery - Manual do Sistema</p>
  </footer>

</body>
</html>