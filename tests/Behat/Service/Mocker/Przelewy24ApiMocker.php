<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Service\Mocker;

use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;
use Sylius\Behat\Service\Mocker\MockerInterface;

final class Przelewy24ApiMocker
{
    /**
     * @var MockerInterface
     */
    private $mocker;

    /**
     * @param MockerInterface $mocker
     */
    public function __construct(MockerInterface $mocker)
    {
        $this->mocker = $mocker;
    }

    /**
     * @param callable $action
     */
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