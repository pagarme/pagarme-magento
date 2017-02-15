Feature: Recipient
 Como cliente da Pagar.me integrando uma aplicação PHP
 Eu quero uma camada de abstração
 Para que eu possa manter recebedores

  Scenario Outline: Creating recipient with bank account data
    Given recipient data "<interval>", "<day>", "<transfer>", "<anticipation>" and "<percentage>"
    And bank data "<bank_code>", "<agencia>", "<agencia_dv>", "<conta>", "<conta_dv>", "<document>" and "<legal_name>"
    When register the recipient
    Then a recipient must be created
    Examples:
    | interval  | day   | transfer  | anticipation  | percentage    | bank_code | agencia   | agencia_dv    | conta | conta_dv  | document      | legal_name |
    | null      | null  | null      | null          | null          | 237       | 1383      | 0             | 13399 | 1         | 44318031144   | João Silva |
    | daily     | 0     | true      | false         | 50            | 341       | 1384      | 1             | 13400 | 2         | 16360841843   | Maria Silva|
    | weekly    | 3     | false     | true          | null          | 001       | 1385      | 2             | 13401 | 2         | 19050151434   | José Silva |
    | monthly   | 15    | null      | null          | 50            | 033       | 1385      | 3             | 13405 | x         | 74748484225   | Luiza Silva|

Scenario Outline: Creating recipient previous created bank account
    Given bank data "<bank_code>", "<agencia>", "<agencia_dv>", "<conta>", "<conta_dv>", "<document>" and "<legal_name>"
    And recipient data "<interval>", "<day>", "<transfer>", "<anticipation>" and "<percentage>"
    When register the account
    And register the recipient with registred account
    Then a recipient must be created
    Examples:
    | interval  | day   | transfer  | anticipation  | percentage    | bank_code | agencia   | agencia_dv    | conta | conta_dv  | document      | legal_name |
    | null      | null  | null      | null          | null          | 237       | 1383      | 0             | 13399 | 1         | 44318031144   | João Silva |
    | daily     | 0     | true      | false         | 50            | 341       | 1384      | 1             | 13400 | 2         | 16360841843   | Maria Silva|
    | weekly    | 3     | false     | true          | null          | 001       | 1385      | 2             | 13401 | 2         | 19050151434   | José Silva |
    | monthly   | 15    | null      | null          | 50            | 033       | 1385      | 3             | 13405 | 3         | 74748484225   | Luiza Silva|

Scenario: Listing recipients
    Given previous created recipients
    When query for recipients
    Then a list of recipients must be returned

Scenario: Getting a recipient
    Given previous created recipient
    When query specific
    Then a recipient must be returned

Scenario: Updating a recipient
    Given previous created recipient
    When I change the recipient transfer interval to weekly
    And make update
    Then the transfer interval must be weekly

Scenario: Getting recipient balance
    Given previous created recipient
    When I query balance
    Then a balance must be returned
