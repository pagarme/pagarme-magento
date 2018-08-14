<img src="https://cdn.rawgit.com/pagarme/brand/9ec30d3d4a6dd8b799bca1c25f60fb123ad66d5b/logo-circle.svg" width="127px" height="127px" align="left"/>

# Pagar.me Magento

Módulo de integração Pagar.me para Magento 1.x

<br>
 
[![Build Status](https://travis-ci.org/pagarme/pagarme-magento.svg?branch=v2)](https://travis-ci.org/pagarme/pagarme-magento)
[![Coverage Status](https://coveralls.io/repos/github/pagarme/pagarme-magento/badge.svg?branch=v2)](https://coveralls.io/github/pagarme/pagarme-magento?branch=master)

## Requisitos

- [Magento Community](https://magento.com/products/community-edition) 1.7.x, 1.8.x ou 1.9.x.
- [PHP](http://php.net) >= 5.4.x

## Instalação 

1. Solicite a última versão do nosso módulo através do e-mail: magento@pagar.me
2. Descompacte o arquivo **zip** e copie as pastas `app` e `vendor` para a a pasta raiz da sua instalação do Magento
3. Limpe o cache em `Sistema > Gerenciamento de Cache`

## Configuração

1. Acesse o painel administrativo da sua loja
2. Vá em `Sistema > Configuração > Métodos de Pagamento > Pagar.me`
3. Informe sua **Chave de API** e sua **Chave de criptografia**
4. Salve as configurações
5. Em `Sistema > Configuração > Configuração do cliente > Opções de Nome e Endereço`, altere o valor dos campos:
* `Número de linhas em um endereço de rua` com valor `4`
*  `Exibir Tax/Vat` com valor `Habilitado`
7. Salve as configurações

## Para desenvolvedores - Avançado

### Requisitos

- [Docker](https://docs.docker.com)
- [Docker Compose](https://docs.docker.com/compose/)

### Instalando o Magento Community 1.x

1. Execute o comando `docker-compose up -d` para iniciar os containers e a instalação do Magento
2. Execute o comando `docker-compose logs -f magento` para acompanhar o processo de instalação.
3. Execute o comando `docker run -it --rm -v $(pwd):/code -w /code pagarme/composer install` para a instalação das dependências do projeto através do [Composer](https://getcomposer.org/).

### Executando o PHPCS (Code sniffer)

Execute o comando: `docker-compose exec magento vendor/bin/phpcs --standard=phpcsruleset.xml <dir|file>`

### Executando os testes unitários

Execute o comando `docker-compose exec magento vendor/bin/phpunit` para iniciar os testes

### Executando os testes de comportamento

Execute o comando `docker-compose exec magento vendor/bin/behat` para iniciar os testes

### Acompanhando a execução dos testes de comportamento

1. Instale um cliente VNC. Sugerimos o [Vinagre](https://wiki.gnome.org/Apps/Vinagre)
2. Conecte-se no servidor.
*  Utilize `localhost` para o host e `secret` para senha

### Testando postbacks em ambiente de desenvolvimento

**Requisitos**

- [Ngrok](https://ngrok.com/)
- Developer mode do magento habilitado ou a variável de ambiente `PAGARME_DEVELOPMENT=enabled`

1. Instale e inicie o ngrok com `ngrok http 80` 
2. Acesse o painel administrativo da loja
3. Vá `Sistema > Configuração > Métodos de Pagamento > Pagar.me`
4. Preencha o campo `Postback URL` com a url gerada pelo ngrok
5. Crie uma transação
6. Uma vez criada uma transação basta executar alguma operação que invoque um postback: estorno, pagamento de boleto, etc.

### Acessando a loja virtual através do navegador

1. Altere seu arquivo `/etc/hosts` adicionando a entrada `127.0.0.1 magento`
2. Acesse a loja no navegador utilizando o endereço `http://magento`

