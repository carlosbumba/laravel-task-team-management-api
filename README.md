# Task & Team Management API

API modular e segura para gerenciamento de tarefas e equipes, construída com Laravel. Utiliza autenticação via Sanctum (Bearer Token), aplica rate limit padrão em todas as rotas e implementa controle de acesso com policies baseadas em papéis (admin, manager, member). Todas as ações críticas são auditadas de forma assíncrona com filas (Laravel Queue) e armazenadas usando Spatie Activity Log. Projeto estruturado com Clean Architecture e Domain-Driven Design (DDD).

---

## 📁 Estrutura Modular (DDD)

Organização por contexto/domínio:

```
- Auth/
- User/
- Task/
- Team/
- Audit/
- Shared/ (Enums, base classes, exceptions)
```

Cada módulo segue a separação:

- `Domain/`: Entidades, Enums, Events
- `Application/`: UseCases, Services, DTOs
- `Infrastructure/`: Models, Jobs, Policies, Observers, Repositories
- `Interface/`: Controllers, Resources, Requests, Routes

---

## 🔧 Instalação

```bash
git clone https://github.com/carlosbumba/laravel-task-team-management-api.git
cd seu-projeto

composer install

cp .env.example .env
php artisan key:generate

# Configure seu banco de dados em .env e depois
php artisan migrate

# Rodar fila para logs e tarefas assíncronas
php artisan queue:work

# Iniciar servidor local
php artisan serve
```

---

## 🔐 Autenticação

- Utiliza **Laravel Sanctum** com autenticação via Bearer Token.
- Aplique o token usando o header `Authorization: Bearer {token}` em todas as rotas protegidas.
- Todas as rotas estão protegidas por **rate limiting** padrão.

---

## 📊 Decisões Técnicas

| Técnica                          | Justificativa                                                           |
| -------------------------------- | ----------------------------------------------------------------------- |
| Clean Architecture + DDD         | Escalabilidade, testabilidade e separação de responsabilidades          |
| Modularização por contexto       | Organização lógica por domínio: `Task`, `Team`, `User`, `Audit`         |
| Spatie Activity Log              | Logs completos de ações, com metadados e histórico                      |
| Queue com Jobs (`ShouldQueue`)   | Processamento assíncrono de logs e tarefas                              |
| Observers + AuditLogger          | Disparo automático de logs sem acoplamento                              |
| DTOs e UseCases                  | Isolamento da lógica de negócio                                         |
| Policies granulares              | Controle de acesso baseado em papéis (admin, manager, member)           |
| PestPHP                          | Testes rápidos, legíveis e abrangentes                                  |
| FormRequests customizados        | Validação e mensagens claras, isoladas por contexto                     |
| Laravel Sanctum                  | Autenticação leve e moderna via API Token                               |
| Rate Limiting                    | Limitação de requisições protegendo recursos da API                     |
| Scribe/Scamble                   | Documentação gerada a partir das anotações em código                    |
| Carregamento dinâmico de módulos | `ModulesServiceProvider` registra providers automaticamente por domínio |

---

### 🧩 Arquitetura Modular com Autoregistro

Para este projeto foi desenvolvido um `ModulesServiceProvider` que registra automaticamente todos os service providers dentro de `Modules/*/Providers/*ServiceProvider.php`.  
Isso permite adicionar novos domínios (ex: `Billing`, `Chat`) sem modificar o `bootstrap/providers.php`, mantendo a aplicação escalável e desacoplada.

```php
   // registrar todos os módulos dinamicamente :)
foreach (glob(app_path('Modules/*/Providers/*ServiceProvider.php')) as $provider) {
    $class = str_replace([app_path(), '/', '.php',], ['App', '\\', ''], $provider);
    $class = rtrim(str_replace('App\\Modules\\', '', $class));

    $this->app->register($class);
}
```

---

## 🧪 Testes

Rodar todos os testes:

```bash
./vendor/bin/pest
```

Verificar cobertura:

```bash
./vendor/bin/pest --coverage
```

Os testes cobrem:

- Controllers
- UseCases
- Services
- Jobs (assíncronos)
- FormRequests
- Policies
- Observers
- Auditoria via fila

---

## 📘 Documentação da API

Gerada automaticamente com [DeDoc Scramble](https://scramble.dedoc.co):

Acesse em: `http://localhost:8000/docs/api`

---

## ✅ Versão e Base URL

- Versão da API: `v1`
- Base URL: `http://localhost/api/v1`

---

## 📅 Pendências / Melhorias futuras

- 🔐 Finalizar configuração completa de WebSocket com canais privados autenticados
- 📄 Criar interface de visualização para logs de auditoria

---

## 🧩 Principais Dependências Utilizadas

| Pacote                                 | Uso no Projeto                                                                 |
|----------------------------------------|---------------------------------------------------------------------------------|
| **`dedoc/scramble`**                   | Gera documentação da API automaticamente a partir de anotações nos controllers. Ideal para projetos com arquitetura modular e sem acoplamento ao Swagger tradicional. |
| **`f9webltd/laravel-api-response-helpers`** | Fornece helpers para respostas JSON padronizadas (`respondOk`, `respondError`, etc.), reduzindo repetição e mantendo consistência entre endpoints. |
| **`laravel/reverb`**                   | Suporte a WebSockets nativo no Laravel. Estrutura de base para implementar notificações em tempo real com eventos e canais privados. |
| **`laravel/sanctum`**                  | Gerencia autenticação via API tokens (Bearer), simples e leve. Protege rotas da API com middleware `auth:sanctum`. |
| **`spatie/laravel-activitylog`**       | Registra logs de ações importantes (como criação, edição e exclusão de tarefas/equipes). Armazena dados antigos e novos, com suporte a usuários e modelos envolvidos. |

---

## 👤 Autor

✅ Desenvolvido por Carlos Bumba como parte de um desafio técnico profissional.
