@managing_payment_method_przelewy24
Feature: Adding a new payment method
    In order to pay for orders in different ways
    As an Administrator
    I want to add a new payment method to the registry

    Background:
        Given the store operates on a single channel in "United States"
        And I am logged in as an administrator

    @ui
    Scenario: Adding a new Przelewy24 payment method
        Given I want to create a new Przelewy24 payment method
        When I name it "Przelewy24" in "English (United States)"
        And I specify its code as "przelewy24_test"
        And I configure it with test Przelewy24 credentials
        And I add it
        Then I should be notified that it has been successfully created
        And the payment method "Przelewy24" should appear in the registry
