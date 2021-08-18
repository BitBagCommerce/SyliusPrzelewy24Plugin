<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Context\Ui\Admin;

use Behat\Behat\Context\Context;
use FriendsOfBehat\PageObjectExtension\Page\SymfonyPageInterface;
use Sylius\Behat\Service\Resolver\CurrentPageResolverInterface;
use Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Page\Admin\PaymentMethod\CreatePageInterface;

final class ManagingPaymentMethodPrzelewy24Context implements Context
{
    private CurrentPageResolverInterface $currentPageResolver;

    private CreatePageInterface $createPage;

    public function __construct(
        CurrentPageResolverInterface $currentPageResolver,
        CreatePageInterface $createPage
    ) {
        $this->createPage = $createPage;
        $this->currentPageResolver = $currentPageResolver;
    }

    /**
     * @Given I want to create a new Przelewy24 payment method
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
