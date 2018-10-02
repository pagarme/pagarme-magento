Feature: Credit Card
    As an administrator of a webstore
    I want to use the transparent checkout for credit card purchases with installments
    So that the clients on my store can buy their goods without knowing that the payment is resolved by Pagar.me


    Scenario Outline: Make a purchase by credit card
        Given a registered user
        And the administrator set payment action to "<payment_action>"
        And the administrator set the async configuration to "<isAsync>"
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with transparent checkout using credit card
        And I confirm my payment information
        And place order
        Then the purchase must be paid with success
        And I get the created order id from success page
        And the order status should be "<expected_order_status>"
        Examples:
        | payment_action    | isAsync | expected_order_status    |
        | authorize_capture | no      | processing               |
        | authorize_only    | no      | pending_payment          |
        | authorize_only    | yes     | pending_payment          |
        | authorize_capture | yes     | pending_payment          |

    Scenario: Make a sync refused order by credit card
        Given a registered user
        And the administrator set the async configuration to "no"
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with transparent checkout using credit card
        And I give a invalid payment information
        And place order
        Then I must stay in the checkout page

    Scenario: Make an async refused order by credit card
        Given a registered user
        And the administrator set the async configuration to "yes"
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with transparent checkout using credit card
        And I give a invalid payment information
        And place order
        Then the purchase must be paid with success
        And I get the created order id from success page
        And the order status should be "pending_payment"

    Scenario Outline: Change the max installments configuration
        Given a registered user
        When I set max installments to "<max_installments>"
        And I set the minimum installment amount to "<min_installment_amount>"
        And I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with transparent checkout using credit card
        And I confirm my payment information
        And I should see only installment options up to "<desired_max_installment>"
        And place order
        Then the purchase must be paid with success
        Examples:
        | max_installments  | min_installment_amount | desired_max_installment |
        | 12                | 1                      | 12                      |
        | 5                 | 4.99                   | 3                       |

    Scenario: Make a purchase by credit card with interest and installments
        Given a registered user
        When I set max installments to 10
        And I set interest rate to 10
        And I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with transparent checkout using credit card
        And I choose 10
        And I confirm my payment information
        And place order
        Then the checkout order review interest amount should be "16.22"
        And the purchase must be created with value based on both 10 and 10

    @order_view_interest @skipTest
    Scenario: Check the interest in the order details page
        Given a registered user
        And set a max installment as "10" and interest rate as "10"
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with transparent checkout using credit card
        And I choose 10
        And I confirm my payment information
        And place order
        Then the purchase must be paid with success
        And I get the created order id
        And I check the order interest amount in its detail page
        And the interest value should consider the values "10" and "10"

    @admin_order_view_interest @skipTest
    Scenario: Check the interest in the admin order details page
        Given a registered user
        And set a max installment as "10" and interest rate as "10"
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with transparent checkout using credit card
        And I choose 10
        And I confirm my payment information
        And place order
        Then the purchase must be paid with success
        And I get the created order id
        And I login to the admin
        And I check the order interest amount in its admin detail page
        And the admin interest value should consider the values "10" and "10"

    @admin_order_view_payment_details @skipTest
    Scenario: Check the interest and payment method in the admin order details page
        Given a registered user
        And set a max installment as "10" and interest rate as "10"
        When I access the store page
        And add any product to basket
        And I go to checkout page
        And login with registered user
        And confirm billing and shipping address information
        And choose pay with transparent checkout using credit card
        And I choose 2
        And I confirm my payment information
        And place order
        Then the purchase must be paid with success
        And I get the created order id
        And I login to the admin
        And I check the order payment details
        And the admin details should contain the payment method "Credit Card", installments value "2", customer name and card brand

    Scenario: Check if in an existing order's invoice has the interest value
        Given a existing order
        When I login to the admin
        And I check the invoice interest amount in its admin detail page
        Then the interest value should be "11.22" in the invoice details

    @capture_online @skipTest
    Scenario: Capture a purchase by credit card through the platform
        Given a created order authorized only
        When I login to the admin
        And I go to order details page
        And click on the invoice button
        And select to capture amount "online"
        And click on the submit invoice button
        Then the order should be captured on Pagar.me
