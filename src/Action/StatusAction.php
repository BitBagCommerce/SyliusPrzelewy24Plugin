<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Action;

use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;
use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Exception\RequestNotSupportedException;
use Sylius\Component\Core\Model\PaymentInterface;

final class StatusAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * @var Przelewy24BridgeInterface
     */
    private $przelewy24Bridge;

    /**
     * @param Przelewy24BridgeInterface $przelewy24Bridge
     */
    public function __construct(Przelewy24BridgeInterface $przelewy24Bridge)
    {
        $this->przelewy24Bridge = $przelewy24Bridge;
    }

    /**
     * {@inheritDoc}
     */
    public function setApi($api): void
    {
        if (false === is_array($api)) {
            throw new UnsupportedApiException('Not supported.Expected to be set as array.');
        }

        $this->przelewy24Bridge->setAuthorizationData($api['merchant_id'], $api['crc_key'], $api['environment']);
    }

    /**
     * {@inheritDoc}
     *
     * @param GetStatusInterface $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        /** @var PaymentInterface $payment */
        $payment = $request->getModel();

        $details = $payment->getDetails();

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        if (isset($httpRequest->query['status']) &&
            $httpRequest->query['status'] === Przelewy24BridgeInterface::CANCELLED_STATUS
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

        if (Przelewy24BridgeInterface::FAILED_STATUS === $details['p24_status']) {
            $request->markFailed();
            return;
        }

        $request->markUnknown();
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request): bool
    {
        return
            $request instanceof GetStatusInterface &&
            $request->getModel() instanceof PaymentInterface
        ;
    }
}
