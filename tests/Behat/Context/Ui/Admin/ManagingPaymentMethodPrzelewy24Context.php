<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use Sylius\Behat\Page\SymfonyPageInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Page\Admin\PaymentMethod\CreatePageInterface;

final class ManagingPaymentMethodPrzelewy24Context implements Context
{
    /** @var CurrentPageResolverInterface */
    private $currentPageResolver;

    /** @var CreatePageInterface */
    private $createPage;

    /**
     * @param CurrentPageResolverInterface $currentPageResolver
     * @param CreatePageInterface $createPage
     */
    public function __construct(
        CurrentPageResolverInterface $currentPageResolver,
        CreatePageInterface $createPage
    ) {
        $this->createPage = $createPage;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @Given I want to create a new Przelewy24 payment method
     *
     * @throws \Sylius\Behat\Page\UnexpectedPageException
     */
    public function iWantToCreateANewPrzelewy24PaymentMethod(): void
    {
        $this->createPage->open(['factory' => 'przelewy24']);
    }

    /**
     * @When I configure it with test Przelewy24 credentials
     */
    public function iConfigureItWithTestPrzelewy24Credentials(): void
    {
        $this->resolveCurrentPage()->setEnvironment('sandbox');
        $this->resolveCurrentPage()->setMerchantId('test');
        $this->resolveCurrentPage()->setCrcKey('test');
    }

    /**
     * @return CreatePageInterface|SymfonyPageInterface
     */
    private function resolveCurrentPage(): SymfonyPageInterface
    {
        return $this->currentPageResolver->getCurrentPageWithForm([
            $this->createPage,
        ]);
    }
}
