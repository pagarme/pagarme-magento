Feature: Configuration Form
    As a administrator of webstore
    I want to change api key, encription key and the other configs
    So that I can customize module behavior

    Scenario: Configuring basic module options
        Given a admin user
        And a api key
        And a encryption key
        And a credit card list to allow
        When I access the admin
        And go to system configuration page
        And insert an API key
        And insert an encryption key
        And enable Pagar.me Checkout
        And turn on customer data capture
        And change the boleto helper text
        And change the credit card helper text
        And change the ui color
        And change the header text
        And change the payment button text
        And change the checkout button text
        And change payment method title
        And select the allowed credit cards
        And save configuration
        And I set interest rate to "10"
        And I set free instalments to "2"
        And I set max instalments to "12"
        And I set installments minimum value to "10"
        And I set boleto discount to "20.72"
        And I set boleto discount mode to "percentage"
        Then the configuration must be saved with success
        And Pagar.me checkout must be enabled
        And the credit card list must be saved in database
