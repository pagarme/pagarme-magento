# Magento Extension for [Pagar.me](https://pagar.me) Payment Gateway (Magento CE 1.7+)

## Instalação

### Instalar usando o [modgit](https://github.com/jreinke/modgit):

    $ cd /path/to/magento
    $ modgit init
    $ modgit add pagarme git@github.com:pagarme/pagarme-magento.git

### Instalar usando o [modman](https://github.com/colinmollenhour/modman):

    $ cd /path/to/magento
    $ modman init
    $ modman clone git@github.com:pagarme/pagarme-magento.git

### ou baixar e instalar manualmente:

* Baixe a ultima versão [aqui](https://github.com/pagarme/pagarme-magento/archive/master.zip)
* Descompacte o arquivo baixado e copie as pastas ``` app```, ```js``` e ```skin``` para dentro do diretório principal do Magento
* Limpe a cache em ```Sistema > Gerenciamento de Cache```

## Campo customizado para CPF / Cnpj

* Por padrão o módulo trabalha com o CPF / Cnpj pelo campo taxvat, mas caso você utilize algum campo customizado para estes fields, basta que você configure os campos em ``` sistema > Configuração > Formas de Pagamento > Pagar.me - Configuração``` e informe no campo CPF o name do input customizado de CPF e em CPNJ o name do input customizado do CNPJ.

## Configuração

* Configure o modulo em ```Sistema > Configuração > Métodos de Pagamento > Pagar.me - Configuração``` e informe a ```Chave de API``` e a ```Chave de criptografia```, obtidos a partir da sua conta no [Pagar.me](https://pagar.me)
* Para configurar o Boleto, vá em ```Sistema > Configuração > Métodos de Pagamento > Pagar.me - Boleto```
* Para configurar o Cartão de Crédito, vá em ```Sistema > Configuração > Métodos de Pagamento > Pagar.me - Cartão de Crédito```
* Caso o checkout que você utilize tenha um evento personalizado no on-change do botão finalizar compra diferente de "payment", vá em ```Sistema > Configuração > Métodos de Pagamento > Pagar.me - Checkout``` e adicione no campo "Checkout payment onclick save" o value do on-change personalizado do seu checkout.

## Observações
* Por padrão o modulo utiliza o campo ```taxvat``` como ```document_number``` e 4 linhas em um endereço(```Sistema > Configuração > Configuração do cliente > Opções de Nome e Endereço``` e o campo ```Número de linhas em um endereço de rua``` com valor ```4```) respectivamente a ```street```, ```street_number```, ```complementary``` e ```neighborhood```.  Mas não se preocupe, caso esteja utilizando diferente do padrão, você pode utilizar o observer ```pagarme_get_customer_info_from_order_after``` e definir os valores de acordo com o seu Magento

## Tests
wget https://phar.phpunit.de/phpunit-4.1.0.phar
chmod +x phpunit-4.1.0.phar
mv phpunit-4.1.0.phar /usr/bin/phpunit

