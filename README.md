# API Pesquisas de Opinião

## Qual é a função dessa API?

Essa API foi criada para servir ao sistema `Pesquisas de Opinião` (repositório lucasmagalhaesp/pesquisas), separando a toda a lógica de programação, acesso ao banco de dados, geração de relatórios, etc, do front-end do sistema.

## Tecnologias Utilizadas na API

Esse projeto é uma API desenvolvida na linguagem PHP com o Laravel Framework (versão 11), servindo o sistema `Pesquisas de Opinião` (repositório lucasmagalhaesp/pesquisas)

## Instale suas dependências
```bash
composer install
```

## Configurações
```bash
Configure seu banco de dados no arquivo .env
```

## Execute suas migrations
```bash
php artisan migrate
```

## Insira um usuário inicial no banco de dados (e-mail: admin@pesquisa.sem, senha: 123456)
```bash
php artisan db:seed
```

## Inicie a aplicação em ambiente de desenvolvimento
```bash
php artisan serve
```
