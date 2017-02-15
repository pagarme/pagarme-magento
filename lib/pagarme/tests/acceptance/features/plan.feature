Feature: Plan
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu possa manter planos

  Scenario Outline: Creating plan
    Given a "<amount>", "<days>" and "<name>"
    And "<trial>", "<methods>", "<charges>", and "<installments>"
    When register the plan
    Then a plan must be created
    And must plan contain same data
    Examples:
    | amount  | days  |  name   | trial | methods | charges | installments  |
    | 100     |  30   | Plano A | null  | null    | null    | null          |
    | 1337    |  15   | Plano B |  7    | null    | null    | null          |
    | 1000001 |  10   | Plano C | null  | boleto  | null    | null          |
    | 50688   |  45   | Plano D | null  | null    | null    | null          |
    | 8008    |  7    | Plano E | null  | null    | 10      | null          |
    | 486     |  35   | Plano F | null  | null    | null    | 6             |
    | 586     |  60   | Plano G | 7     | boleto  | 10      | 5             |

  Scenario: Listing Plans
    Given a previous created plans
    When I query for plans
    Then a list of Plans must be returned

  Scenario: Geting Plans
    Given a previous created plan
    When I query for planId
    Then the same plan must be returned

  Scenario: Updating Plans
    Given a previous created plan
    When I edit the plan name
    And I query for planId
    Then the same plan must be returned
    And the name must be changed