<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Service\Mocker;

use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;
use Sylius\Behat\Service\Mocker\MockerInterface;

final class Przelewy24ApiMocker
{
    /** @var MockerInterface */
    private $mocker;

    public function __construct(MockerInterface $mocker)
    {
        $this->mocker = $mocker;
    }

    public function mockApiSuccessfulVerifyTransaction(callable $action): void
    {
        $mockService = $this->mocker
            ->mockService('bitbag_sylius_przelewy24_plugin.bridge.przelewy24', Przelewy24BridgeInterface::class)
        ;

        $mockService->shouldReceive('setAuthorizationData');
        $mockService->shouldReceive('trnVerify')->andReturn(true);
        $mockService->shouldReceive('createSign')->andReturn('test');

        $action();

        $this->mocker->unmockService('bitbag_sylius_przelewy24_plugin.bridge.przelewy24');
    }
}
