@paying_with_przelewy24_for_order
Feature: Paying with Przelewy24 during checkout
    In order to buy products
    As a Customer
    I want to be able to pay with Przelewy24

    Background:
        Given the store operates on a single channel in "United States"
        And there is a user "john@bitbag.pl" identified by "password123"
        And the store has a payment method "Przelewy24" with a code "przelewy24" and Przelewy24 Checkout gateway
        And the store has a product "PHP T-Shirt" priced at "€19.99"
        And the store ships everywhere for free
        And I am logged in as "john@bitbag.pl"

    @ui
    Scenario: Successful payment
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "Przelewy24" payment method
        When I confirm my order with Przelewy24 payment
        And I sign in to Przelewy24 and pay successfully
        Then I should be notified that my payment has been completed

    @ui
    Scenario: Cancelling the payment
        Given I added product "PHP T-Shirt" to the cart
        And I have proceeded selecting "Przelewy24" payment method
        When I confirm my order with Przelewy24 payment
        And I cancel my Przelewy24 payment
        Then I should be notified that my payment has been cancelled
        And I should be able to pay again
