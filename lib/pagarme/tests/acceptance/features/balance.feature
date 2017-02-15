Feature: Balance
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu possa consultar o saldo da companhia

  Scenario: Get company balance
    When I query for balance
    Then a balance must be returned
