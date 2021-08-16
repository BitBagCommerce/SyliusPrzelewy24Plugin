<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusPrzelewy24Plugin\Action;

use BitBag\SyliusPrzelewy24Plugin\Action\NotifyAction;
use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Notify;
use PhpSpec\ObjectBehavior;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class NotifyActionSpec extends ObjectBehavior
{
    function let(Przelewy24BridgeInterface $przelewy24Bridge): void
    {
        $this->beConstructedWith($przelewy24Bridge);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(NotifyAction::class);
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
        Notify $request,
        \ArrayObject $arrayObject,
        GatewayInterface $gateway
    ): void {
        $this->setGateway($gateway);

        $arrayObject->offsetSet('p24_session_id', 'test');

        $request->getModel()->willReturn($arrayObject);

        $this
            ->shouldThrow(NotFoundHttpException::class)
            ->during('execute', [$request])
        ;
    }

    function it_supports_only_notify_request_and_array_access(
        Notify $request,
        \ArrayAccess $arrayAccess
    ): void {
        $request->getModel()->willReturn($arrayAccess);

        $this->supports($request)->shouldReturn(true);
    }
}
