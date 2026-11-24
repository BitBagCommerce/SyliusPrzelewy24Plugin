<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Service\Mocker;

use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;

final class Przelewy24Bridge implements Przelewy24BridgeInterface
{
    public const TEST_TOKEN = 'test-token';

    public function __construct(private readonly Przelewy24BridgeInterface $decorated)
    {
    }

    public function setAuthorizationData(
        string $merchantId,
        string $crcKey,
        string $environment = self::SANDBOX_ENVIRONMENT,
    ): void {
        $this->decorated->setAuthorizationData($merchantId, $crcKey, $environment);
    }

    public function getTrnRegisterUrl(): string
    {
        return $this->decorated->getTrnRegisterUrl();
    }

    public function getTrnRequestUrl(string $token): string
    {
        return $this->decorated->getTrnRequestUrl($token);
    }

    public function getTrnVerifyUrl(): string
    {
        return $this->decorated->getTrnVerifyUrl();
    }

    public function getHostForEnvironment(): string
    {
        return $this->decorated->getHostForEnvironment();
    }

    public function createSign(array $parameters): string
    {
        return 'test';
    }

    public function trnRegister(array $posData): string
    {
        return self::TEST_TOKEN;
    }

    public function trnVerify(array $posData): bool
    {
        return true;
    }

    public function request(array $posData, string $url): array
    {
        return [];
    }
}
