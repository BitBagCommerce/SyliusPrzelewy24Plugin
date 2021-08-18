<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Bridge;

interface Przelewy24BridgeInterface
{
    public const SANDBOX_ENVIRONMENT = 'sandbox';

    public const PRODUCTION_ENVIRONMENT = 'production';

    public const SANDBOX_HOST = 'https://sandbox.przelewy24.pl/';

    public const PRODUCTION_HOST = 'https://secure.przelewy24.pl/';

    public const P24_API_VERSION = '3.2';

    public const COMPLETED_STATUS = 'completed';

    public const FAILED_STATUS = 'failed';

    public const CANCELLED_STATUS = 'cancelled';

    public const CREATED_STATUS = 'created';

    public function getTrnRegisterUrl(): string;

    public function getTrnRequestUrl(string $token): string;

    public function getTrnVerifyUrl(): string;

    public function getHostForEnvironment(): string;

    public function setAuthorizationData(
        string $merchantId,
        string $crcKey,
        string $environment = self::SANDBOX_ENVIRONMENT
    ): void;

    public function createSign(array $parameters): string;

    public function trnRegister(array $posData): string;

    public function trnVerify(array $posData): bool;

    public function request(array $posData, string $url): array;
}
