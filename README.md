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

## Configuração

* Configure o modulo em ```Sistema > Configuração > Métodos de Pagamento > Pagar.me - Configuração``` e informe a ```Chave de API``` e a ```Chave de criptografia```, obtidos a partir da sua conta no [Pagar.me](https://pagar.me)
* Para configurar o Boleto, vá em ```Sistema > Configuração > Métodos de Pagamento > Pagar.me - Boleto```
* Para configurar o Cartão de Crédito, vá em ```Sistema > Configuração > Métodos de Pagamento > Pagar.me - Cartão de Crédito```
* Caso o checkout que você utilize tenha um evento personalizado no on-change do botão finalizar compra diferente de "payment", vá em ```Sistema > Configuração > Métodos de Pagamento > Pagar.me - Checkout``` e adicione no campo "Checkout payment onclick save" o value do on-change personalizado do seu checkout.

## Observações
* Por padrão o modulo utiliza o campo ```taxvat``` como ```document_number``` e 4 linhas em um endereço(```Sistema > Configuração > Configuração do cliente > Opções de Nome e Endereço``` e o campo ```Número de linhas em um endereço de rua``` com valor ```4```) respectivamente a ```street```, ```street_number```, ```complementary``` e ```neighborhood```.  Mas não se preocupe, caso esteja utilizando diferente do padrão, você pode utilizar o observer ```pagarme_get_customer_info_from_order_after``` e definir os valores de acordo com o seu Magento

## Marketplace

### Configurações Gerais:
* Para habilitar a opção de marketplace você ir há ```Sistema > Configuração > Métodos de Pagamento > Pagar.me - Configuração``` e habilitar a opção ```Enable Marketplace Split```.

## Fluxo de Cadastro de um Seller (Vendedor) no seu marketplace:

### Criando Conta Bancária para um seller (Vendedor)
* Para criar um seller (Vendedor) em seu marketplace deve ser criado em primeiro lugar uma conta bancária para este seller em ```Pagar.me - Marketplace > Contas Bancárias``` basta clicar no botão (Adicionar nova conta bancária) e preencher todos os dados obrigatórios para criação desta conta bancária.

### Criando um Seller (Vendedor)
* Para criar um novo seller (Vendedor) você deve ir em ```Pagar.me - Marketplace > Recebedores``` e clicar no botão (Adicionar novo recebedor), após clicar no botão você deve preencher todos os dados do formulário:
* Receber Automáticamente : Variável que indica se o recebedor pode receber os pagamentos automaticamente
* Frequência de Pagamentos : Frequência na qual o recebedor irá ser pago. Valores possíveis: Diário, Semanal, Mensal
* Dia de Pagamento : Dia no qual o recebedor vai ser pago.
* Conta Bancária ID : Identificador de uma conta bancária previamente criada (Criada na etapa anterior).

### Criando Regra de Split
* Nesta etapa definimos as regras de split para um seller (Vendedor), uma regra de split é criada para fazer a divisão dos valores de um pedido, informando quanto deve ser pago aos sellers (Vendedores) envolvidos neste pedido e o marketplace. Para criar uma regra de split você deve ir há ```Pagar.me - Marketplace > Regras de Split``` e clicar no botão (Adicionar nova regra de split), após clicar no botão você deve preencher todos os campos do formulário:

* Id Recebedor : Recebedor que irá receber os valores descritos nessa regra.
* Cobrar taxa Pagar.me do recebedor : Define se o recebedor dessa regra irá ser cobrado pela taxa da Pagar.me.
* Recebedor responsável pelo chargeback : Define se o recebedor vinculado a essa regra irá se responsabilizar pelo risco da transação (chargeback)
* Tipo de Split : Hoje só temos disponível no módulo a opção de %. 
* Valor : Porcentagem que o recebedor vai receber do valor da transação. 
* Receber % do frete : Aqui você informa se este recebedor vai receber % da valor do frete, exemplo : se existe um pedido que deve ser dividido entre 2 recebedores com um frete de 100 reais cada recebedor vai receber 50 reais no split dele referente ao valor do frete caso este campo esteja marcado como sim nos dois recebedores

### Criando menu (Cardápio) de produtos de um seller (Vendedor)
* Esta opção é aonde você associa produtos a sellers (Vendedores), para associar um produto a um recebedor você deve acessar ```Pagar.me - Marketplace > Associar produtos a recebedores``` em clicar em (Adicionar novo produto a um recebedor), após clicar no botão você deve preencher todos os campos do formulário:
* Sku : Sku do produto que deseja associar ao recebedor.
* Id Recebedor : Id do recebedor que você deseja associar o produto.


