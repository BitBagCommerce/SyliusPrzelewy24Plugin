<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Context\Ui\Shop;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\Shop\Checkout\CompletePageInterface;
use Sylius\Behat\Page\Shop\Order\ShowPageInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Page\External\Przelewy24CheckoutPageInterface;

final class Przelewy24CheckoutContext implements Context
{
    /** @var CompletePageInterface */
    private $summaryPage;

    /** @var Przelewy24CheckoutPageInterface */
    private $przelewy24CheckoutPage;

    /** @var ShowPageInterface */
    private $orderDetails;

    /** @var EntityRepository */
    private $paymentRepository;

    /**
     * @param CompletePageInterface $summaryPage
     * @param Przelewy24CheckoutPageInterface $przelewy24CheckoutPage
     * @param ShowPageInterface $orderDetails
     * @param EntityRepository $paymentRepository
     */
    public function __construct(
        CompletePageInterface $summaryPage,
        Przelewy24CheckoutPageInterface $przelewy24CheckoutPage,
        ShowPageInterface $orderDetails,
        EntityRepository $paymentRepository
    ) {
        $this->summaryPage = $summaryPage;
        $this->przelewy24CheckoutPage = $przelewy24CheckoutPage;
        $this->orderDetails = $orderDetails;
        $this->paymentRepository = $paymentRepository;
    }

    /**
     * @When I confirm my order with Przelewy24 payment
     * @Given I have confirmed my order with Przelewy24 payment
     */
    public function iConfirmMyOrderWithPrzelewy24Payment(): void
    {
        $this->summaryPage->confirmOrder();
    }

    /**
     * @When I sign in to Przelewy24 and pay successfully
     *
     * @throws \Behat\Mink\Exception\DriverException
     * @throws \Behat\Mink\Exception\UnsupportedDriverActionException
     */
    public function iSignInToPrzelewy24AndPaySuccessfully(): void
    {
        $this->przelewy24CheckoutPage->pay();
    }

    /**
     * @When I try to pay again Przelewy24 payment
     */
    public function iTryToPayAgainPrzelewy24Payment(): void
    {
        $this->orderDetails->pay();
    }

    /**
     * @When I cancel my Przelewy24 payment
     * @Given I have canceled Przelewy24 payment
     */
    public function iFailedMyPrzelewy24Payment(): void
    {
        $this->przelewy24CheckoutPage->failedPayment();
    }
}
