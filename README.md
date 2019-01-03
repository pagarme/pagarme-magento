<img src="https://cdn.rawgit.com/pagarme/brand/9ec30d3d4a6dd8b799bca1c25f60fb123ad66d5b/logo-circle.svg" width="127px" height="127px" align="left"/>

# Pagar.me Magento

Módulo de integração Pagar.me para Magento 1.x

<br>
 
[![Build Status](https://travis-ci.org/pagarme/pagarme-magento.svg?branch=v2)](https://travis-ci.org/pagarme/pagarme-magento)
[![Coverage Status](https://coveralls.io/repos/github/pagarme/pagarme-magento/badge.svg?branch=v2)](https://coveralls.io/github/pagarme/pagarme-magento?branch=master)

## Requisitos

- [Magento Community](https://magento.com/products/community-edition) 1.7.x, 1.8.x ou 1.9.x.
- [PHP](http://php.net) >= 5.4.x
- Cron

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
8. Vá em `Sistema > Configuração > Catálogo > Inventário > Opções de estoque`
* Altere a opção `Reajustar Estoque Quando Pedidor for Cancelado` para `Sim`

### Configuração de cancelamento automático de boletos não pagos

Pedidos que forem criados na plataforma com boleto como forma de pagamento, 
deverão ser cancelados após o vencimento. O módulo possui um processo 
automatizado que, identifica os boletos pendentes e, se em **4** dias após a 
data de vencimento não houver o pagamento, o pedido é **cancelado**.

Para que este processo funcione é preciso que as a _cron_ da plataforma seja 
configurada no servidor:

`*/5 * * * * sh /path/to/your/magento/site/root/cron.sh`

A instrução acima irá executar o módulo de gerenciamento de tarefas agendadas
 a cada 5 minutos.

Mais detalhes sobre esta configuração no [link](https://amasty.com/blog/configure-magento-cron-job/)

## Para desenvolvedores - Avançado

### Requisitos

- [Docker](https://docs.docker.com)
- [Docker Compose](https://docs.docker.com/compose/)
- [GNU Make (Opcional)](https://www.gnu.org/software/make/)

### Instalando o Magento Community 1.x

1. Clonando o projeto
```
git clone git@github.com:pagarme/pagarme-magento.git
```

2. Preparando o ambiente (containers)

**Obs:** Utilizamos o `make` para tornar a utilização mais amigável mas é possível obter os mesmos resultados executando comandos através dos containers utilizando o `docker-compose`. Para isso basta consultar o `Makefile` do projeto.

```
make
```

O comando acima irá:
- Subir os containers docker
- Instalar as dependências do projeto através do [Composer](https://getcomposer.org)
- Habilitar os logs da plataforma (system e exception)
- Habilitar a exibição de erros da plataforma

### Executando o PHPCS (Code sniffer)

```
make phpcs target=NOME DO ARQUIVO OU DIRETORIO
```

### Executando os testes unitários

```make test-unit```

### Executando os testes de comportamento

a) Todas as suites de teste
```make test-e2e```

b) Uma suite específica. Veja todas as disponíveis no [behat.yml](https://github.com/pagarme/pagarme-magento/blob/v2/behat.yml#L12) do projeto
```make test-e2e-suite suite=NOME_DA_SUITE```

### Acompanhando a execução dos testes de comportamento

1. Instale um cliente VNC. Sugerimos o [Vinagre](https://wiki.gnome.org/Apps/Vinagre)
2. Conecte-se no servidor.
*  Utilize `localhost` para o host e `secret` para senha

### Comandos úteis para desenvolvimento

Todos os comandos podem ser conferidos no arquivo [Makefile](https://github.com/pagarme/pagarme-magento/blob/v2/Makefile) do projeto

1. "Matando" os containers
```
make down
```

2. Acompanhando (tail -f) os logs do magento
```
make tailf-system-logs
ou
make tailf-exception-logs
```

3. Recuperando api key (Pagar.me) configurada no módulo
```
make get-api-key
```

4. Alterando api key (Pagar.me)
```
make set-api-key api_key=SUA_API_KEY
```

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

