Feature: Postback
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu possa manipular postbacks

  Scenario: Query postbacks
    Given a previous created transaction
    When I query for postbacks
    Then a array of Postback must be returned

  Scenario: Query postbacks
    Given a previous created transaction
    And I query for postbacks
    When query for the first postback
    Then the same postback must be returned
