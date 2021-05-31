<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="250"></a></p>

## Laravel com Mandrill

Projeto feito em Laravel, que possui uma API responsável por comunicação via e-mail. A API é integrada com o serviço de e-mail transacional do Mandrill que realiza disparos de e-mails agendados pelos seus consumidores.

Requerimentos:
- PHP >= 7.3
- MySQL

## Baixar o projeto
Primeiro passo, clonar o projeto:
``` bash
# Clonar
git clone https://github.com/GeisonPiegas/laravel-mandrill.git

# Acessar
cd laravel-mandrill
```

## Configuração - API
Primeiro criar um banco de dados com o nome `laravel-mandrill` e depois:
``` bash
# Instalar dependências do projeto.
composer install

# Configurar variáveis de ambiente.
cp .env.example .env
php artisan key:generate

# Criar migrations (Tabelas).
php artisan migrate

# Em caso de utilização do S.O linux, necessário permissão na pasta `storage` para os logs.
chmod -R 777 \storage

# Adicionar remetente para envio de e-mail, no arquivo `.env` alterar email@remetente.com pelo e-mail do remetente.
MAILCHIMP_FROM_EMAIL=email@remetente.com

# Rodar em ambiente local .
php artisan serve
```

## Request
``` bash
# Endpoint para acesso local.
http://localhost/api/endpoint/agendar

# Header para autenticação no endpoint.
headers {
    "Content-Type": "application/json",
    "key": "jhds476$78%96@DSD$"
}

# Modelo de JSON que o endpoint espera receber para criar ou agendar envio de e-mail.
{
	"nome": "Fulando da Silva", # Obrigatório
	"email": "fulano@teste.com.br", # Obrigatório
	"assunto": "Teste", # Obrigatório
	"corpo_email": "Olá mundo!", # Obrigatório
	"agendar": null # Se não preenchido o e-mail é disparado imediatamente, e se preenchido, o sistema agenda o disparo.
}
```

## Logs
O sistema mantem os logs de todos os disparos e agendamentos.

``` bash
# Novo e-mail
storage/logs/create_mail

# Envio de e-mail
storage/logs/sent_mail

# Erros no envio de e-mail
storage/logs/error_mail
```

## Envio de e-mails
Após rodar o servidor, possui duas formas de realizar os envios de e-mails:
``` bash
# Primeiro com a utilização da CRON no servidor para executar automaticamente os comandos, 
# já encontra-se configurado, só adicionar o supervisor como no link abaixo.
https://laravel.com/docs/8.x/queues#configuring-supervisor

# Segundo rodando manualmente os comandos, segue abaixo eles:
# Comando para buscar os e-mails a serem enviados e criar um JOB de envio.
php artisan mail:send

# Comando para executar os JOBs criados pelo comando anterior e realizar os envios.
php artisan queue:work --queue=mail-send
```
