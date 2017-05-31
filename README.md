<img src="https://cdn.rawgit.com/pagarme/brand/9ec30d3d4a6dd8b799bca1c25f60fb123ad66d5b/logo-circle.svg" width="127px" height="127px" align="left"/>

# Pagar.me Magento

Módulo de integração Pagar.me para Magento 1.x

<br>
 
[![Build Status](https://travis-ci.org/pagarme/pagarme-magento.svg?branch=v2)](https://travis-ci.org/pagarme/pagarme-magento)
[![Coverage Status](https://coveralls.io/repos/github/pagarme/pagarme-magento/badge.svg?branch=v2)](https://coveralls.io/github/pagarme/pagarme-magento?branch=master)

## Requisitos

- [Magento Community](https://magento.com/products/community-edition) 1.5.x, 1.6.x, 1.7.x, 1.8.x ou 1.9.x.
- [PHP](http://php.net) >= 5.4.x

## Instalação 

1. Solicite a última versão do nosso módulo através do e-mail: magento@pagar.me
2. Descompacte o arquivo **zip** e copie as pastas `app`, `js`, `skin` e `vendor` para a a pasta raiz da sua instalação do Magento
3. Limpe o cache em `Sistema > Gerenciamento de Cache

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

- [Docker Compose](https://docs.docker.com/compose/)

### Instalando o Magento Community 1.x

1. Execute o comando `docker-compose up -d` para iniciar os containers
2. Execute o comando `docker-compose exec magento install` para executar a instalação do Magento. Caso queira alguma configuração específica, verifique o arquivo `.env`.

### Executando os testes unitários

Execute o comando `docker-compose exec magento php vendor/bin/phpunit` para iniciar os testes

### Executando os testes de comportamento

Execute o comando `docker-compose exec magento php vendor/bin/behat` para iniciar os testes

### Acompanhando a execução dos testes de comportamento
