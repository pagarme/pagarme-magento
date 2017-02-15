Feature: Search
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu possa realizar buscas sobre objetos

  Scenario: Search transactions
    Given a previous created transactions
    When I query for transactions created today
    Then a set of results must be returned
