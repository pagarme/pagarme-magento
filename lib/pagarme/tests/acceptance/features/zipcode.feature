Feature: Zipcode
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu possa obter informações de determinado CEP

  Scenario Outline: Getting CEP
    Given a zipcode "<zipcode>"
    When I query for the CEP
    Then at least city, state and zipcode must be returned
    Examples:
      | zipcode   |
      | 09450000  |
      | 01034020  |
      | 23914330  |
