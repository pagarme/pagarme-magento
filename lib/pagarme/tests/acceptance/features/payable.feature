Feature: Payable
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu possa listar recebiveis

  Scenario: List payables
    Given a transaction with installments
    When I query for payables
    Then a list of payables must be returned
