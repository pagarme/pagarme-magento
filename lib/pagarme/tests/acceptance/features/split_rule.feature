Feature: Split Rule
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu possa realizar transações com split rule

  Scenario: Create a creditcard transaction with split rule
    Given a valid customer
    And valid splitRule
    And a valid card
    When make a credit card transaction
    Then a transaction must be created
    And the transaction must contain split rule

  Scenario: Create a boleto transaction with split rule
    Given a valid customer
    And valid splitRule
    When make a boleto transaction
    Then a transaction must be created
    And the transaction must contain split rule
