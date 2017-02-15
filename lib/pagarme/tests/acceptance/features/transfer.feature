Feature: Transfer
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu possa manter transferencias

  Scenario: Create a transfer with a recipient
    Given a valid recipient
    And available funds
    When make transfer with amount of "5000"
    Then a transfer must be created
    And amount must be the same

  Scenario: Create a transfer with a recipient and custom bank account
    Given a valid recipient
    And available funds
    When make transfer with amount of "5000" to specific bank account
    Then a transfer must be created
    And amount must be the same

  Scenario: Retrieve a transfer
    Given a previous created transfer
    When I query for the transfer
    Then a transfer must be returned
    And must be the same transfer

  Scenario: Retrieve transfers
    Given a previous created transfers
    When I query for the transfers
    Then a list of transfer must be returned

  Scenario: Cancel a transfer
    Given a previous created transfer
    When I cancel the transfer
    Then the same transfer must be returned as canceled
