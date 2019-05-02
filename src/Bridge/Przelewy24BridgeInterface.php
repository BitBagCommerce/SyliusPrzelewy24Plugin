<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Bridge;

interface Przelewy24BridgeInterface
{
    const SANDBOX_ENVIRONMENT = 'sandbox';
    const PRODUCTION_ENVIRONMENT = 'production';
    const SANDBOX_HOST = 'https://sandbox.przelewy24.pl/';
    const PRODUCTION_HOST = 'https://secure.przelewy24.pl/';
    const P24_API_VERSION = '3.2';
    const COMPLETED_STATUS = 'completed';
    const FAILED_STATUS = 'failed';
    const CANCELLED_STATUS = 'cancelled';

    public function getTrnRegisterUrl(): string;

    public function getTrnRequestUrl(string $token): string;

    /** @return string */
    public function getTrnVerifyUrl(): string;

    /** @return string */
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
