<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Page\Admin\PaymentMethod;

use Sylius\Behat\Page\Admin\Crud\CreatePage as BaseCreatePage;

final class CreatePage extends BaseCreatePage implements CreatePageInterface
{
    public function setEnvironment(string $environment): void
    {
        $this->getDocument()->selectFieldOption('Environment', $environment);
    }

    public function setMerchantId(string $merchantId): void
    {
        $this->getDocument()->fillField('Merchant id', $merchantId);
    }

    public function setCrcKey(string $crcKey): void
    {
        $this->getDocument()->fillField('CRC Key', $crcKey);
    }
}
