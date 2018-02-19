Feature: Place order
  In order to buy a product
  As a buyer
  I need to be able to place a order without problems

  Scenario: try Place order
    Given a product created
    When I add the product to the cart
    And I go to the checkout page
    And I fill all my informations
    And place the order
    Then I should be in the success page
