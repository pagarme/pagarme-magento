Feature: Payment method availability
    As a Magento admin
    I want to enable or disable a given payment method as I wish
    So that I can receive payments in the most appropriate way

    Scenario: Disable payment method
        Given a payment method boleto
        When I disable this payment method
        Then the payment method must be disabled

    Scenario: Enable payment method
        Given a payment method boleto
        When I enable this payment method
        Then the payment method must be enabled

    Scenario: Enable both payment methods
        Given the payment methods boleto and credit card
        When I enable both payment methods
        Then both payment methods must be enabled
