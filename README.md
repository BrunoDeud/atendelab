# AtendeLab
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-00000F?style=for-the-badge&logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-563D7C?style=for-the-badge&logo=bootstrap&logoColor=white)

> Sistema web de Controle de Atendimentos Acadêmicos, desenvolvido como projeto prático para a disciplina de **Fábrica de Software**.

O **AtendeLab** tem como objetivo simplificar e digitalizar o registro, gerenciamento e acompanhamento de atendimentos, oferecendo um painel administrativo dinâmico e intuitivo baseado na arquitetura MVC.

---

## 🚀 Tecnologias Utilizadas

**Back-end & Banco de Dados:**
* PHP 8.x
* MySQL & phpMyAdmin

**Front-end:**
* HTML5 & CSS3
* Bootstrap 5
* JavaScript (Fetch API / Async Await)

**Ferramentas & Infraestrutura:**
* Git e GitHub
* XAMPP (Ambiente de desenvolvimento local)

---

## Funcionalidades

- [x] Login e Autenticação de Usuários
- [x] Dashboard interativo com contadores em tempo real
- [x] Cadastro e gerenciamento de pessoas atendidas
- [x] Cadastro de tipos de atendimento
- [x] Registro e atualização de status de atendimentos (Aberto, Em Andamento, Concluído)
- [ ] Geração de Relatórios dinâmicos
- [ ] Página Pública (Landing Page)

---

## Estrutura do Projeto

O projeto foi construído utilizando o padrão de arquitetura **MVC (Model-View-Controller)**, organizando as responsabilidades de forma escalável:

```text
atendelab/
├── app/
│   ├── Controllers/       # Lógica de negócio e comunicação com o banco (ex: DashboardController)
│   ├── Models/            # Entidades e abstração do banco de dados
│   └── Views/             # Interfaces de usuário (HTML/PHP mesclado)
├── config/
│   └── database.php       # Configuração e conexão com o banco de dados (PDO)
├── database/
│   └── atendelab.sql      # Script de criação das tabelas do banco
├── public/
│   └── index.php          # Ponto de entrada (Front Controller)
├── routes.php             # Arquivo de roteamento do sistema
└── README.md
