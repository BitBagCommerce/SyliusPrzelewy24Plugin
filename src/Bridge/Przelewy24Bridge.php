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

use GuzzleHttp\ClientInterface;

final class Przelewy24Bridge implements Przelewy24BridgeInterface
{
    /**
     * @var string
     */
    private $merchantId;

    /**
     * @var string
     */
    private $crcKey;

    /**
     * @var string
     */
    private $environment = self::SANDBOX_ENVIRONMENT;

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * {@inheritDoc}
     */
    public function setAuthorizationData(
        string $merchantId,
        string $crcKey,
        string $environment = self::SANDBOX_ENVIRONMENT
    ): void
    {
        $this->merchantId = $merchantId;
        $this->crcKey = $crcKey;
        $this->environment = $environment;
    }

    /**
     * {@inheritDoc}
     */
    public function getTrnRegisterUrl(): string
    {
        return $this->getHostForEnvironment() . 'trnRegister';
    }

    /**
     * {@inheritDoc}
     */
    public function getTrnRequestUrl(string $token): string
    {
        return $this->getHostForEnvironment() . 'trnRequest/' . $token;
    }

    /**
     * {@inheritDoc}
     */
    public function getTrnVerifyUrl(): string
    {
        return $this->getHostForEnvironment() . 'trnVerify';
    }

    /**
     * {@inheritDoc}
     */
    public function getHostForEnvironment(): string
    {
        return self::SANDBOX_ENVIRONMENT === $this->environment ?
            self::SANDBOX_HOST : self::PRODUCTION_HOST
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function createSign(array $parameters): string
    {
        return md5(implode('|', array_merge($parameters, [$this->crcKey])));
    }

    /**
     * {@inheritDoc}
     */
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

    /**
     * {@inheritDoc}
     */
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
            $this->crcKey,
        ]);

        $posData['p24_sign'] = $sign;

        return (int)$this->request($posData, $this->getTrnVerifyUrl())['error'] === 0;
    }

    /**
     * {@inheritDoc}
     */
    public function request(array $posData, string $url): array
    {
        $response = (string)$this->client->request('POST', $url, ['form_params' => $posData])->getBody();

        $result = [];

        foreach (explode('&', $response) as $value) {
            $value = explode('=', $value);

            $result[trim($value[0])] = $value[1] ?? null;
        }

        if (!isset($result['error']) || $result['error'] > 0) {
            throw new \Exception($response);
        }

        return $result;
    }
}
