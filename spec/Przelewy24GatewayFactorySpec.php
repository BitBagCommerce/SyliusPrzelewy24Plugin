<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusPrzelewy24Plugin;

use BitBag\SyliusPrzelewy24Plugin\Przelewy24GatewayFactory;
use Payum\Core\GatewayFactory;
use PhpSpec\ObjectBehavior;

final class Przelewy24GatewayFactorySpec extends ObjectBehavior
{
    function it_is_initializable(): void
    {
        $this->shouldHaveType(Przelewy24GatewayFactory::class);
        $this->shouldHaveType(GatewayFactory::class);
    }

    function it_populateConfig_run(): void
    {
        $this->createConfig([]);
    }
}
