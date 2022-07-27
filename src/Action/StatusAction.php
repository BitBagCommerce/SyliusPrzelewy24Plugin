<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Action;

use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\GetStatusInterface;
use Sylius\Component\Core\Model\PaymentInterface;

final class StatusAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    private Przelewy24BridgeInterface $przelewy24Bridge;

    public function __construct(Przelewy24BridgeInterface $przelewy24Bridge)
    {
        $this->przelewy24Bridge = $przelewy24Bridge;
    }

    public function setApi($api): void
    {
        if (false === is_array($api)) {
            throw new UnsupportedApiException('Not supported.Expected to be set as array.');
        }

        $this->przelewy24Bridge->setAuthorizationData($api['merchant_id'], $api['crc_key'], $api['environment']);
    }

    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getModel();

        $details = $payment->getDetails();

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        if (isset($httpRequest->query['status']) &&
            Przelewy24BridgeInterface::CANCELLED_STATUS === $httpRequest->query['status']
        ) {
            $details['p24_status'] = Przelewy24BridgeInterface::CANCELLED_STATUS;
            $request->markCanceled();

            return;
        }

        if (false === isset($details['p24_status'])) {
            $request->markNew();

            return;
        }

        if (Przelewy24BridgeInterface::COMPLETED_STATUS === $details['p24_status']) {
            $request->markCaptured();

            return;
        }

        if (Przelewy24BridgeInterface::CREATED_STATUS === $details['p24_status']) {
            $request->markPending();

            return;
        }

        if (Przelewy24BridgeInterface::FAILED_STATUS === $details['p24_status']) {
            $request->markFailed();

            return;
        }

        $request->markUnknown();
    }

    public function supports($request): bool
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}
