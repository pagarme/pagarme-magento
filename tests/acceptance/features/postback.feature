Feature: Update order status
    As a seller that use Magento
    I want receive updates about transactions status
    To keep order workflow updated and financial health of my store

    Scenario: Receive boleto order status update to paid
        Given a pending boleto order
        When a "boleto" order be paid
        Then the order status must be updated to "processing"

    Scenario: Receive boleto order status update to refunded
        Given a pending boleto order
        When a "boleto" order be paid
        And the "boleto" payment be refunded
        Then the order status must be updated to "closed"

    Scenario: Receiving credit card order status update to paid
        Given a pending credit card order
        When a "creditcard" order be paid
        Then the order status must be updated to "processing"

    Scenario: Receive credit card order status update to refunded
        Given a pending credit card order
        When a "creditcard" order be paid
        And the "creditcard" payment be refunded
        Then the order status must be updated to "closed"

    Scenario: Receiving credit card order status update to refused
        Given a pending credit card order
        When a "creditcard" order be refused
        Then the order status must be updated to "canceled"
