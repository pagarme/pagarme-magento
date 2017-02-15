Feature: Calculation
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu possa calcular a incidencia de juros sobre parcelas

  Scenario Outline: Calculating installments
    Given a "<amount>"
    And a "<interest_rate>" in "<installments>"
    And with "<free_installments>"
    When calculate installments
    Then the number of calculated instalmments must be "<installments>"
    Examples:
      | amount  | interest_rate  | free_installments  | installments |
      | 10000   | 10             | 4                  | 4            |
      | 240000  | 5              | 4                  | 10           |
      | 134001  | 55             | 5                  | 9            |
