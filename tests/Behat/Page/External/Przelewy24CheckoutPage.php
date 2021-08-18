<?php

/*
 * This file was created by developers working at BitBag
 * Do you need more information about us and what we do? Visit our https://bitbag.io website!
 * We are hiring developers from all over the world. Join us and start your new, exciting adventure and become part of us: https://bitbag.io/career
*/

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Page\External;

use Behat\Mink\Session;
use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;
use FriendsOfBehat\PageObjectExtension\Page\Page;
use FriendsOfBehat\SymfonyExtension\Mink\MinkParameters;
use Payum\Core\Security\TokenInterface;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\EntityRepository;
use Sylius\Component\Core\Model\PaymentInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Service\Mocker\Przelewy24ApiMocker;

final class Przelewy24CheckoutPage extends Page implements Przelewy24CheckoutPageInterface
{
    private Przelewy24ApiMocker $przelewy24ApiMocker;

    private RepositoryInterface $securityTokenRepository;

    private EntityRepository $paymentRepository;

    private AbstractBrowser $client;

    public function __construct(
        Session $session,
        MinkParameters $parameters,
        Przelewy24ApiMocker $przelewy24ApiMocker,
        RepositoryInterface $securityTokenRepository,
        EntityRepository $paymentRepository,
        AbstractBrowser $client
    ) {
        parent::__construct($session, $parameters);

        $this->przelewy24ApiMocker = $przelewy24ApiMocker;
        $this->paymentRepository = $paymentRepository;
        $this->securityTokenRepository = $securityTokenRepository;
        $this->client = $client;
    }

    public function pay(): void
    {
        $captureToken = $this->findToken('after');
        $notifyToken = $this->findToken('notify');

        $postData = [
            'p24_session_id' => $this->getSessionId($captureToken),
            'p24_order_id' => 'test',
            'p24_sign' => 'test',
            'p24_amount' => 'test',
            'p24_currency' => 'test',
        ];

        $this->przelewy24ApiMocker->mockApiSuccessfulVerifyTransaction(function () use ($notifyToken, $postData, $captureToken): void {
            $this->client->request('POST', $notifyToken->getTargetUrl(), $postData);
            $this->getDriver()->visit($captureToken->getTargetUrl());
        });
    }

    public function failedPayment(): void
    {
        $captureToken = $this->findToken('after');

        $this->getDriver()->visit($captureToken->getTargetUrl() . '&' . http_build_query(['status' => Przelewy24BridgeInterface::CANCELLED_STATUS]));
    }

    protected function getUrl(array $urlParameters = []): string
    {
        return 'https://sandbox.przelewy24.pl/';
    }

    private function findToken(string $type = 'capture'): TokenInterface
    {
        $tokens = [];

        /** @var TokenInterface $token */
        foreach ($this->securityTokenRepository->findAll() as $token) {
            if (strpos($token->getTargetUrl(), $type)) {
                $tokens[] = $token;
            }
        }

        if (count($tokens) > 0) {
            return end($tokens);
        }

        throw new \RuntimeException('Cannot find capture token, check if you are after proper checkout steps');
    }

    private function getSessionId(TokenInterface $token): string
    {
        /** @var PaymentInterface $payment */
        $payment = $this->paymentRepository->find($token->getDetails()->getId());

        return $payment->getDetails()['p24_session_id'];
    }
}
