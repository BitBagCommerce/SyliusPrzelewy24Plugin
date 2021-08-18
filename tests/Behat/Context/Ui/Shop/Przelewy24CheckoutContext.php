<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface;
use Sylius\Behat\Page\Shop\Order\ShowPageInterface;
use Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Page\External\Przelewy24CheckoutPageInterface;
use Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Service\Mocker\Przelewy24ApiMocker;

final class Przelewy24CheckoutContext implements Context
{
    private CompletePageInterface $summaryPage;

    private Przelewy24CheckoutPageInterface $przelewy24CheckoutPage;

    private ShowPageInterface $orderDetails;

    private Przelewy24ApiMocker $przelewy24ApiMocker;

    public function __construct(
        CompletePageInterface $summaryPage,
        Przelewy24CheckoutPageInterface $przelewy24CheckoutPage,
        ShowPageInterface $orderDetails,
        Przelewy24ApiMocker $przelewy24ApiMocker
    ) {
        $this->summaryPage = $summaryPage;
        $this->przelewy24CheckoutPage = $przelewy24CheckoutPage;
        $this->orderDetails = $orderDetails;
        $this->przelewy24ApiMocker = $przelewy24ApiMocker;
    }

    /**
     * @When I confirm my order with Przelewy24 payment
     * @Given I have confirmed my order with Przelewy24 payment
     */
    public function iConfirmMyOrderWithPrzelewy24Payment(): void
    {
        $this->przelewy24ApiMocker->mockApiSuccessfulVerifyTransaction(function (): void {
            $this->summaryPage->confirmOrder();
        });
    }

    /**
     * @When I sign in to Przelewy24 and pay successfully
     */
    public function iSignInToPrzelewy24AndPaySuccessfully(): void
    {
        $this->przelewy24ApiMocker->mockApiSuccessfulVerifyTransaction(function (): void {
            $this->przelewy24CheckoutPage->pay();
        });
    }

    /**
     * @When I try to pay again Przelewy24 payment
     */
    public function iTryToPayAgainPrzelewy24Payment(): void
    {
        $this->przelewy24ApiMocker->mockApiSuccessfulVerifyTransaction(function (): void {
            $this->orderDetails->pay();
        });
    }

    /**
     * @When I cancel my Przelewy24 payment
     * @Given I have canceled Przelewy24 payment
     */
    public function iFailedMyPrzelewy24Payment(): void
    {
        $this->przelewy24ApiMocker->mockApiSuccessfulVerifyTransaction(function (): void {
            $this->przelewy24CheckoutPage->failedPayment();
        });
    }
}
