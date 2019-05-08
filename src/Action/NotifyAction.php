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
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\InvalidArgumentException;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\Notify;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class NotifyAction implements ActionInterface, ApiAwareInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /** @var Przelewy24BridgeInterface */
    private $przelewy24Bridge;

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

        $details = ArrayObject::ensureArrayObject($request->getModel());

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        if (!isset($httpRequest->request['p24_session_id']) || $details['p24_session_id'] !== $httpRequest->request['p24_session_id']) {
            throw new NotFoundHttpException();
        }

        if (false === $this->verifySign($httpRequest)) {
            throw new InvalidArgumentException('Invalid sign.');
        }

        $details['p24_order_id'] = $httpRequest->request['p24_order_id'];

        if (true === $this->przelewy24Bridge->trnVerify($this->getPosData($details))) {
            $details['p24_status'] = Przelewy24BridgeInterface::COMPLETED_STATUS;

            return;
        }

        $details['p24_status'] = Przelewy24BridgeInterface::FAILED_STATUS;
    }

    public function supports($request): bool
    {
        return
            $request instanceof Notify &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }

    private function getPosData(ArrayObject $details): array
    {
        $posData = [];

        $posData['p24_session_id'] = $details['p24_session_id'];
        $posData['p24_amount'] = $details['p24_amount'];
        $posData['p24_currency'] = $details['p24_currency'];
        $posData['p24_order_id'] = $details['p24_order_id'];

        return $posData;
    }

    private function verifySign(GetHttpRequest $request): bool
    {
        $sign = $this->przelewy24Bridge->createSign([
            $request->request['p24_session_id'],
            $request->request['p24_order_id'],
            $request->request['p24_amount'],
            $request->request['p24_currency'],
        ]);

        return $sign === $request->request['p24_sign'];
    }
}
