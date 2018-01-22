<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    /**
     * {@inheritdoc}
     */
    public function setEnvironment(string $environment): void
    {
        $this->getDocument()->selectFieldOption('Environment', $environment);
    }

    /**
     * {@inheritdoc}
     */
    public function setMerchantId(string $merchantId): void
    {
        $this->getDocument()->fillField('Merchant id', $merchantId);
    }

    /**
     * {@inheritdoc}
     */
    public function setCrcKey(string $crcKey): void
    {
        $this->getDocument()->fillField('CRC Key', $crcKey);
    }
}
