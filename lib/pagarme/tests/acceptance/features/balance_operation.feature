Feature: Balance Operations
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu possa listar operações de saldo

  Scenario: List balance operations
    Given a transaction with installments
    When I query for balance operations
    Then a list of balance operations must be returned
