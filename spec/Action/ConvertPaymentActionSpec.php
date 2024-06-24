<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace spec\BitBag\SyliusPrzelewy24Plugin\Action;

use BitBag\SyliusPrzelewy24Plugin\Action\ConvertPaymentAction;
use Doctrine\Common\Collections\ArrayCollection;
use Payum\Core\Action\ActionInterface;
use Payum\Core\Request\Convert;
use PhpSpec\ObjectBehavior;
use Sylius\Bundle\PayumBundle\Provider\PaymentDescriptionProviderInterface;
use Sylius\Component\Core\Model\CustomerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Core\Model\ProductInterface;

final class ConvertPaymentActionSpec extends ObjectBehavior
{
    function let(PaymentDescriptionProviderInterface $paymentDescriptionProvider): void
    {
        $this->beConstructedWith($paymentDescriptionProvider);
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(ConvertPaymentAction::class);
    }

    function it_implements_action_interface(): void
    {
        $this->shouldHaveType(ActionInterface::class);
    }

    function it_executes(
        Convert $request,
        PaymentInterface $payment,
        OrderInterface $order,
        CustomerInterface $customer,
        ArrayCollection $items,
        \ArrayIterator $arrayIterator,
        PaymentDescriptionProviderInterface $paymentDescriptionProvider,
        OrderItemInterface $orderItem,
        ProductInterface $product
    ): void {
    $customer->getEmail()->willReturn('user@example.com');
    $customer->getId()->willReturn(1);

    $orderItem->getProduct()->willReturn($product);
    $orderItem->getQuantity()->willReturn(1);
    $orderItem->getUnitPrice()->willReturn(445535);
    $arrayIterator->valid()->willReturn(true, false);
    $arrayIterator->current()->willReturn($orderItem);
    $arrayIterator->next()->shouldBeCalled();
    $arrayIterator->rewind()->shouldBeCalled();

    $items->getIterator()->willReturn($arrayIterator);

    $order->getNumber()->willReturn(000001);
    $order->getCustomer()->willReturn($customer);
    $order->getLocaleCode()->willReturn('pl_PL');
    $order->getCurrencyCode()->willReturn('USD');
    $order->getShippingAddress()->willReturn(null);
    $order->getItems()->willReturn($items);

    $payment->getOrder()->willReturn($order);
    $payment->getId()->willReturn(1);
    $payment->getAmount()->willReturn(445535);
    $payment->getCurrencyCode()->willReturn('PLN');

    $paymentDescriptionProvider->getPaymentDescription($payment)->willReturn('description');

    $request->getSource()->willReturn($payment);
    $request->getTo()->willReturn('array');
    $request->setResult([
        'p24_amount' => 445535,
        'p24_currency' => 'PLN',
        'p24_description' => 'description',
        'p24_language' => 'pl_PL',
        'p24_email' => 'user@example.com',
        'p24_name_1' => null,
        'p24_quantity_1' => 1,
        'p24_price_1' => 445535
    ])->shouldBeCalled();

    $this->execute($request);
}

    function it_supports_only_convert_request_payment_source_and_array_to(
        Convert $request,
        PaymentInterface $payment
    ): void {
        $request->getSource()->willReturn($payment);
        $request->getTo()->willReturn('array');

        $this->supports($request)->shouldReturn(true);
    }
}
