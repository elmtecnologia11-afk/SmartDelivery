# SmartDelivery - Sistema de Delivery

Sistema completo para pedidos de delivery com painel administrativo.

## Hospedagem no Railway

1. Acesse [railway.app](https://railway.app) e crie uma conta
2. Clique em "New Project" > "Deploy from GitHub repo"
3. Selecione o repositório `SmartDelivery`
4. Adicione um plugin MySQL:
   - Clique em "New" > "Database" > "MySQL"
5. Configure as variáveis de ambiente:
   - `DB_HOST` = host do MySQL (aparece no plugin)
   - `DB_PORT` = 3306
   - `DB_NAME` = smartdelivery
   - `DB_USER` = usuário do MySQL
   - `DB_PASS` = senha do MySQL
6. Acesse `https://seu-app.up.railway.app/setup.php` para criar as tabelas
7. Acesse `https://seu-app.up.railway.app/` para usar o sistema

## Variáveis de Ambiente Opcionais

- `WHATSAPP_NUMERO` - Número do WhatsApp para pedidos
- `NOME_LOJA` - Nome da loja
- `HORARIO_FUNCIONAMENTO` - Horário de funcionamento

## Estrutura

- `/` - Página principal (pedidos)
- `/admin/` - Painel administrativo
- `/api/` - APIs para pedidos e produtos
- `/setup.php` - Configuração do banco de dados