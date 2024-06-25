<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Bridge;

use GuzzleHttp\ClientInterface;

final class Przelewy24Bridge implements Przelewy24BridgeInterface
{
    private string $merchantId = '';

    private string $crcKey = '';

    private string $environment = self::SANDBOX_ENVIRONMENT;

    public function __construct(
        private ClientInterface $client,
    ) {
    }

    public function setAuthorizationData(
        string $merchantId,
        string $crcKey,
        string $environment = self::SANDBOX_ENVIRONMENT
    ): void {
        $this->merchantId = $merchantId;
        $this->crcKey = $crcKey;
        $this->environment = $environment;
    }

    public function getTrnRegisterUrl(): string
    {
        return $this->getHostForEnvironment() . 'trnRegister';
    }

    public function getTrnRequestUrl(string $token): string
    {
        return $this->getHostForEnvironment() . 'trnRequest/' . $token;
    }

    public function getTrnVerifyUrl(): string
    {
        return $this->getHostForEnvironment() . 'trnVerify';
    }

    public function getHostForEnvironment(): string
    {
        return self::SANDBOX_ENVIRONMENT === $this->environment ?
            self::SANDBOX_HOST : self::PRODUCTION_HOST
        ;
    }

    public function createSign(array $parameters): string
    {
        return md5(implode('|', array_merge($parameters, [$this->crcKey])));
    }

    public function trnRegister(array $posData): string
    {
        $posData['p24_merchant_id'] = $this->merchantId;
        $posData['p24_pos_id'] = $this->merchantId;
        $posData['p24_api_version'] = self::P24_API_VERSION;

        $sign = $this->createSign([
            $posData['p24_session_id'],
            $posData['p24_merchant_id'],
            $posData['p24_amount'],
            $posData['p24_currency'],
        ]);

        $posData['p24_sign'] = $sign;

        return $this->request($posData, $this->getTrnRegisterUrl())['token'];
    }

    public function trnVerify(array $posData): bool
    {
        $posData['p24_merchant_id'] = $this->merchantId;
        $posData['p24_pos_id'] = $this->merchantId;
        $posData['p24_api_version'] = self::P24_API_VERSION;

        $sign = $this->createSign([
            $posData['p24_session_id'],
            $posData['p24_order_id'],
            $posData['p24_amount'],
            $posData['p24_currency'],
        ]);

        $posData['p24_sign'] = $sign;

        return 0 === (int) $this->request($posData, $this->getTrnVerifyUrl())['error'];
    }

    public function request(array $posData, string $url): array
    {
        $response = (string) $this->client->request('POST', $url, ['form_params' => $posData])->getBody();

        $result = [];

        foreach (explode('&', $response) as $value) {
            $value = explode('=', $value);

            $result[trim($value[0])] = $value[1] ?? null;
        }

        if (!isset($result['error']) || 0 < $result['error']) {
            throw new \Exception($response);
        }

        return $result;
    }
}
