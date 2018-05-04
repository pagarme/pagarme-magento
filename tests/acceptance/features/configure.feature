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

    Scenario: Enabling capture customer data
        Given a admin user
        When I access the admin
        And go to system configuration page
        And turn on customer data capture
        And save configuration
        Then Pagar.me checkout must be enabled

    Scenario: Customizing checkout
        Given a admin user
        When I access the admin
        And go to system configuration page
        And change the boleto helper text
        And change the credit card helper text
        And change the ui color
        And change the header text
        And change the payment button text
        And change the checkout button text
        And change payment method title
        And save configuration
        Then the configuration must be saved with success

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

    Scenario: Setting up allowed credit card brands
        Given a admin user
        And a credit card list to allow
        When I access the admin
        And go to system configuration page
        And select the allowed credit cards
        And save configuration
        Then the configuration must be saved with success
        And the credit card list must be saved in database

    Scenario Outline: Configure boleto discount
        Given a admin user
        When I access the admin
        And go to system configuration page
        And I set boleto discount to "<boleto_discount>"
        And I set boleto discount mode to "<boleto_discount_mode>"
        And save configuration
        Then the configuration must be saved with success
        Examples:
        | boleto_discount | boleto_discount_mode  |
        | 0               | No discount           |
        | 10.5            | Fixed value           |
        | 20.72           | Percentage            |
