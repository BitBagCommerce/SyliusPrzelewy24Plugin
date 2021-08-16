<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusPrzelewy24Plugin\Action;

use BitBag\SyliusPrzelewy24Plugin\Action\StatusAction;
use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\GetStatusInterface;
use PhpSpec\ObjectBehavior;
use Sylius\Component\Core\Model\PaymentInterface;

final class StatusActionSpec extends ObjectBehavior
{
    function let(Przelewy24BridgeInterface $przelewy24Bridge): void
    {
        $this->beConstructedWith($przelewy24Bridge);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(StatusAction::class);
    }

    function it_implements_action_interface(): void
    {
        $this->shouldHaveType(ActionInterface::class);
    }

    function it_implements_api_aware_interface(): void
    {
        $this->shouldHaveType(ApiAwareInterface::class);
    }

    function it_implements_gateway_aware_interface(): void
    {
        $this->shouldHaveType(GatewayAwareInterface::class);
    }

    function it_executes(
        GetStatusInterface $request,
        PaymentInterface $payment,
        GatewayInterface $gateway
    ): void {
        $this->setApi([
            'merchant_id' => 'test',
            'crc_key' => 'test',
            'environment' => 'sandbox',
        ]);
        $this->setGateway($gateway);

        $payment->getDetails()->willReturn([]);

        $request->getModel()->willReturn($payment);

        $request->markNew()->shouldBeCalled();

        $this->execute($request);
    }

    function it_supports_only_get_status_request_and_array_access(
        GetStatusInterface $request,
        PaymentInterface $payment
    ): void {
        $request->getModel()->willReturn($payment);

        $this->supports($request)->shouldReturn(true);
    }
}
