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
use Symfony\Component\DependencyInjection\ContainerInterface;

final class Przelewy24Bridge implements Przelewy24BridgeInterface
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function setAuthorizationData(
        string $merchantId,
        string $crcKey,
        string $environment = self::SANDBOX_ENVIRONMENT
    ): void {
        $this->container->get('bitbag_sylius_przelewy24_plugin.bridge.przelewy24')->setAuthorizationData(
            $merchantId,
            $crcKey,
            $environment
        );
    }

    public function getTrnRegisterUrl(): string
    {
        return $this->container->get('bitbag_sylius_przelewy24_plugin.bridge.przelewy24')->getTrnRegisterUrl();
    }

    public function getTrnRequestUrl(string $token): string
    {
        return $this->container->get('bitbag_sylius_przelewy24_plugin.bridge.przelewy24')->getTrnRequestUrl($token);
    }

    public function getTrnVerifyUrl(): string
    {
        return $this->container->get('bitbag_sylius_przelewy24_plugin.bridge.przelewy24')->getTrnVerifyUrl();
    }

    public function getHostForEnvironment(): string
    {
        return $this->container->get('bitbag_sylius_przelewy24_plugin.bridge.przelewy24')->getHostForEnvironment();
    }

    public function createSign(array $parameters): string
    {
        return $this->container->get('bitbag_sylius_przelewy24_plugin.bridge.przelewy24')->createSign($parameters);
    }

    public function trnRegister(array $posData): string
    {
        return $this->container->get('bitbag_sylius_przelewy24_plugin.bridge.przelewy24')->trnRegister($posData);
    }

    public function trnVerify(array $posData): bool
    {
        return $this->container->get('bitbag_sylius_przelewy24_plugin.bridge.przelewy24')->trnVerify($posData);
    }

    public function request(array $posData, string $url): array
    {
        return $this->container->get('bitbag_sylius_przelewy24_plugin.bridge.przelewy24')->request($posData, $url);
    }
}
