<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Convert;
use Sylius\Bundle\PayumBundle\Provider\PaymentDescriptionProviderInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class ConvertPaymentAction implements ActionInterface
{
    use GatewayAwareTrait;

    private PaymentDescriptionProviderInterface $paymentDescriptionProvider;

    public function __construct(PaymentDescriptionProviderInterface $paymentDescriptionProvider)
    {
        $this->paymentDescriptionProvider = $paymentDescriptionProvider;
    }

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        /** @var OrderInterface $order */
        $order = $payment->getOrder();

        $paymentData = $this->getPaymentData($payment);
        $customerData = $this->getCustomerData($order);
        $shoppingList = $this->getShoppingList($order);

        $details = array_merge($paymentData, $customerData, $shoppingList);

        $request->setResult($details);
    }

    public function supports($request): bool
    {
        return
            $request instanceof Convert &&
            $request->getSource() instanceof PaymentInterface &&
            $request->getTo() === 'array'
        ;
    }

    private function getPaymentData(PaymentInterface $payment): array
    {
        $paymentData = [];

        $paymentData['p24_amount'] = $payment->getAmount();
        $paymentData['p24_currency'] = $payment->getCurrencyCode();
        $paymentData['p24_description'] = $this->paymentDescriptionProvider->getPaymentDescription($payment);

        return $paymentData;
    }

    private function getCustomerData(OrderInterface $order): array
    {
        $customerData = [];

        $customerData['p24_language'] = $order->getLocaleCode();

        if (null !== $customer = $order->getCustomer()) {
            $customerData['p24_email'] = $customer->getEmail();
        }

        if (null !== $address = $order->getShippingAddress()) {
            $customerData['p24_adress'] = $address->getStreet();
            $customerData['p24_zip'] = $address->getPostcode();
            $customerData['p24_country'] = $address->getCountryCode();
            $customerData['p24_phone'] = $address->getPhoneNumber();
            $customerData['p24_city'] = $address->getCity();
            $customerData['p24_client'] = $address->getFullName();
        }

        return $customerData;
    }

    private function getShoppingList(OrderInterface $order): array
    {
        $shoppingList = [];

        $index = 1;

        /** @var OrderItemInterface $item */
        foreach ($order->getItems() as $item) {
            $shoppingList['p24_name_' . $index] = $item->getProduct()->getName();
            $shoppingList['p24_quantity_' . $index] = $item->getQuantity();
            $shoppingList['p24_price_' . $index] = $item->getUnitPrice();

            ++$index;
        }

        return $shoppingList;
    }
}
