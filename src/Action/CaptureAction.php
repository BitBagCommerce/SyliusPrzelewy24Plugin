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
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpPostRedirect;
use Payum\Core\Request\Capture;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Payum\Core\Security\TokenInterface;

final class CaptureAction implements ActionInterface, ApiAwareInterface, GenericTokenFactoryAwareInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * @var GenericTokenFactoryInterface
     */
    protected $tokenFactory;

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
     * @param GenericTokenFactoryInterface $genericTokenFactory
     *
     * @return void
     */
    public function setGenericTokenFactory(GenericTokenFactoryInterface $genericTokenFactory = null): void
    {
        $this->tokenFactory = $genericTokenFactory;
    }

    /**
     * {@inheritDoc}
     *
     * @param Capture $request
     */
    public function execute($request): void
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = $request->getModel();

        if (isset($details['p24_status'])) {
            return;
        }

        /** @var TokenInterface $token */
        $token = $request->getToken();

        $details['p24_session_id'] = uniqid();

        $notifyToken = $this->tokenFactory->createNotifyToken($token->getGatewayName(), $token->getDetails());

        $details['p24_url_return'] = $token->getTargetUrl();
        $details['p24_url_cancel'] = $token->getAfterUrl() . '&' . http_build_query(['status' => Przelewy24BridgeInterface::CANCELLED_STATUS]);
        $details['p24_wait_for_result'] = '1';
        $details['p24_url_status'] = $notifyToken->getTargetUrl();

        $details['token'] = $this->przelewy24Bridge->trnRegister($details->toUnsafeArray());

        throw new HttpPostRedirect(
            $this->przelewy24Bridge->getTrnRequestUrl($details['token'])
        );
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request): bool
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
