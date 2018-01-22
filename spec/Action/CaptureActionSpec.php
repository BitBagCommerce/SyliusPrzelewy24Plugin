<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace spec\BitBag\SyliusPrzelewy24Plugin\Action;

use BitBag\SyliusPrzelewy24Plugin\Action\CaptureAction;
use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayInterface;
use Payum\Core\Payum;
use Payum\Core\Reply\HttpPostRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Security\GenericTokenFactory;
use Payum\Core\Security\TokenInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Component\Core\Model\PaymentInterface;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\GatewayAwareInterface;

final class CaptureActionSpec extends ObjectBehavior
{
    function let(Przelewy24BridgeInterface $przelewy24Bridge): void
    {
        $this->beConstructedWith($przelewy24Bridge);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(CaptureAction::class);
    }

    function it_implements_action_interface(): void
    {
        $this->shouldHaveType(ActionInterface::class);
    }

    function it_implements_generic_token_factory_aware(): void
    {
        $this->shouldHaveType(GenericTokenFactoryAwareInterface::class);
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
        Capture $request,
        ArrayObject $arrayObject,
        PaymentInterface $payment,
        TokenInterface $token,
        TokenInterface $notifyToken,
        Payum $payum,
        GenericTokenFactory $genericTokenFactory,
        GatewayInterface $gateway,
        Przelewy24BridgeInterface $przelewy24Bridge
    ): void
    {
        $this->setGateway($gateway);

        $this->setApi([
            'merchant_id' => 'test',
            'crc_key' => 'test',
            'environment' => 'sandbox',
        ]);

        $notifyToken->getTargetUrl()->willReturn('url');
        $notifyToken->getHash()->willReturn('test');

        $token->getTargetUrl()->willReturn('url');
        $token->getAfterUrl()->willReturn('url');
        $token->getGatewayName()->willReturn('test');
        $token->getDetails()->willReturn([]);
        $token->getHash()->willReturn('test');

        $genericTokenFactory->createNotifyToken('test', [])->willReturn($notifyToken);

        $this->setGenericTokenFactory($genericTokenFactory);

        $payum->getTokenFactory()->willReturn($genericTokenFactory);

        $arrayObject->toUnsafeArray()->willReturn([]);
        $arrayObject->offsetExists('p24_status')->shouldBeCalled();
        $arrayObject->offsetGet('token')->willReturn('token');
        $arrayObject->offsetSet('token', 'token')->shouldBeCalled();
        $arrayObject->offsetSet("p24_url_cancel", "url&status=cancelled")->shouldBeCalled();
        $arrayObject->offsetSet('p24_url_status', 'url')->shouldBeCalled();
        $arrayObject-> offsetSet('p24_wait_for_result', '1')->shouldBeCalled();
        $arrayObject->offsetSet('p24_url_return', 'url')->shouldBeCalled();
        $arrayObject->offsetSet('p24_session_id', Argument::any())->shouldBeCalled();

        $request->getModel()->willReturn($arrayObject);
        $request->getFirstModel()->willReturn($payment);
        $request->getToken()->willReturn($token);

        $przelewy24Bridge->trnRegister([])->willReturn('token');
        $przelewy24Bridge->getTrnRequestUrl('token')->willReturn('url');

        $this
            ->shouldThrow(HttpPostRedirect::class)
            ->during('execute', [$request])
        ;
    }

    function it_supports_only_capture_request_and_array_access(
        Capture $request,
        \ArrayAccess $arrayAccess
    ): void
    {
        $request->getModel()->willReturn($arrayAccess);

        $this->supports($request)->shouldReturn(true);
    }
}
