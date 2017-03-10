Feature: Update order status
    As a seller that use Magento
    I want receive updates about transactions status
    To keep order workflow updated and financial health of my store

    Scenario: Receiving boleto order status update
        Given a pending boleto order
        When I receive a postback with status "paid"
        Then my order must be updated to "processing"
