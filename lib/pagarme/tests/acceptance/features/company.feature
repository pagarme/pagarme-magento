Feature: Company
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu possa obter informações sobre minha companhia

  Scenario: Getting company info
    Given a configured client
    When I query for company information
    Then company information must be obtained
