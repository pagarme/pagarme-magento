Feature: Configuration Form
    As a administrator of webstore
    I want to change api key, encription key and the other configs
    So that I can customize module behavior

    Scenario: Inserting the API_KEY and EK_KEY
        Given a admin user
        And a api key
        And a encryption key
        When I access the admin
        And go to system configuration page
        And insert an API key
        And insert an encryption key
        And save configuration
        Then the configuration must be saved with success

    Scenario: Enabling module
        Given a admin user
        When I access the admin
        And go to system configuration page
        And enable Pagar.me Checkout
        And save configuration
        Then Pagar.me checkout must be enabled

    Scenario Outline: Configuring installments info
        Given Pagar.me settings panel
        When I set interest rate to "<interest_rate>"
        And I set free instalments to "<free_installments>"
        And I set max instalments to "<max_installments>"
        And save configuration
        Then the configuration must be saved with success
        Examples:
        | interest_rate | free_installments | max_installments  |
        | 10            | 2                 | 12                |
        | 3             | 5                 | 15                |
        | 0             | 3                 | 3                 |
        | 4             | 0                 | 1                 |
        | 0             | 0                 | 1                 |
