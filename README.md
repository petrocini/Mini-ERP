# Mini ERP em PHP Puro

Este é um projeto de mini ERP desenvolvido em PHP puro com arquitetura MVC, utilizando MySQL como banco de dados e Composer para gerenciamento de dependências. Foi desenvolvido com foco em boas práticas, organização de código e arquitetura limpa.

---

## ✅ Requisitos

- PHP >= 8.1 (com `pdo_mysql` habilitado)
- Composer
- Docker 

---

## 🚀 Como rodar o projeto

### 1. Clonar o repositório

```bash
git clone https://github.com/seu-usuario/mini-erp.git
cd mini-erp
```

### 2. Instalar as dependências

```bash
composer install
```

### 3. Configurar o ambiente

Crie um arquivo `.env` na raiz do projeto com o seguinte conteúdo:

```env
DB_HOST=127.0.0.1
DB_NAME=erp
DB_USER=root
DB_PASS=root

MAIL_HOST=smtp.exemplo.com
MAIL_PORT=587
MAIL_USER=seu@email.com
MAIL_PASS=sua_senha
MAIL_FROM=seu@email.com

DEBUG=true
```

> Defina `DEBUG=false` quando não quiser carregar dados de teste.

---

## 🛠 Rodando com Docker 

Se quiser rodar localmente com Docker (MySQL + phpMyAdmin):

```bash
docker-compose up -d
```

- Acesse o phpMyAdmin: [http://localhost:8080](http://localhost:8080)  
- Servidor: `mysql`  
- Usuário: `root`  
- Senha: `root`

---

## 🧱 Executar as migrations

```bash
php database/run_migrations.php
```

Isso executará todas as migrations principais e, se `DEBUG=true`, também executará as migrations da pasta `database/test_migrations/`, como por exemplo:

- Cupons de teste: `DEBUG10` e `DEBUG50`

---

## ▶️ Iniciando o servidor local

```bash
php -S localhost:8000 -t public
```

Acesse em [http://localhost:8000](http://localhost:8000)

---

## 📦 Funcionalidades implementadas

- Cadastro de produtos com variações e controle de estoque
- Carrinho com controle de sessão e regras de frete
- Cupons de desconto com validade e valor mínimo
- Finalização de pedidos com envio de e-mail
- Consulta de endereço via CEP (viaCEP)
- Webhook de atualização/cancelamento de pedidos
- Painel de controle de carrinho com AJAX
- Interface responsiva com Bootstrap 5

---

## ✍️ Autor

Desenvolvido por Lucas Petrocini dos Reis.