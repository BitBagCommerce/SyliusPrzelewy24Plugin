<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin;

use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

final class Przelewy24GatewayFactory extends GatewayFactory
{
    protected function populateConfig(ArrayObject $config): void
    {
        $config->defaults([
            'payum.factory_name' => 'przelewy24',
            'payum.factory_title' => 'Przelewy24',
        ]);

        if (false === (bool) $config['payum.api']) {
            $config['payum.default_options'] = [
                'crc_key' => null,
                'merchant_id' => null,
                'environment' => Przelewy24BridgeInterface::SANDBOX_ENVIRONMENT,
            ];

            $config->defaults($config['payum.default_options']);

            $config['payum.required_options'] = [
                'crc_key',
                'merchant_id',
            ];

            $config['payum.api'] = function (ArrayObject $config) {
                $config->validateNotEmpty($config['payum.required_options']);

                return [
                    'crc_key' => $config['crc_key'],
                    'merchant_id' => $config['merchant_id'],
                    'environment' => $config['environment'],
                    'payum.http_client' => $config['payum.http_client'],
                ];
            };
        }
    }
}
