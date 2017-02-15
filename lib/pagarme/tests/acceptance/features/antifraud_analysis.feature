Feature: Antifraud Analysis
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu detalhar análises de antifraude

  Scenario: Query antifraud analyses
    Given a previous created transaction
    When I query transaction antifraud analyses
    Then a array of Antifraud Analysis must be returned

  Scenario: Query single antifraud analysis
    Given a previous created transaction
    And I query transaction antifraud analyses
    When query for the first antifraud analysis
    Then the same antifraud analysis must be returned
