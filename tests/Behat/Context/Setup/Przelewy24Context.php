<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace Tests\BitBag\SyliusPrzelewy24Plugin\Behat\Context\Setup;

use Behat\Behat\Context\Context;
use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Behat\Service\SharedStorageInterface;
use Sylius\Bundle\CoreBundle\Fixture\Factory\ExampleFactoryInterface;
use Sylius\Component\Core\Model\PaymentMethodInterface;
use Sylius\Component\Core\Repository\PaymentMethodRepositoryInterface;

final class Przelewy24Context implements Context
{
    private SharedStorageInterface $sharedStorage;

    private PaymentMethodRepositoryInterface $paymentMethodRepository;

    private ExampleFactoryInterface $paymentMethodExampleFactory;

    private EntityManagerInterface $paymentMethodManager;

    public function __construct(
        SharedStorageInterface $sharedStorage,
        PaymentMethodRepositoryInterface $paymentMethodRepository,
        ExampleFactoryInterface $paymentMethodExampleFactory,
        EntityManagerInterface $paymentMethodManager
    ) {
        $this->sharedStorage = $sharedStorage;
        $this->paymentMethodRepository = $paymentMethodRepository;
        $this->paymentMethodExampleFactory = $paymentMethodExampleFactory;
        $this->paymentMethodManager = $paymentMethodManager;
    }

    /**
     * @Given the store has a payment method :paymentMethodName with a code :paymentMethodCode and Przelewy24 Checkout gateway
     */
    public function theStoreHasAPaymentMethodWithACodeAndPrzelewy24CheckoutGateway(
        string $paymentMethodName,
        string $paymentMethodCode
    ): void {
        $paymentMethod = $this->createPaymentMethod($paymentMethodName, $paymentMethodCode, 'Przelewy24');

        $paymentMethod->getGatewayConfig()->setConfig([
            'crc_key' => 'test',
            'merchant_id' => 'test',
            'environment' => Przelewy24BridgeInterface::SANDBOX_ENVIRONMENT,
        ]);

        $this->paymentMethodManager->flush();
    }

    private function createPaymentMethod(
        string $name,
        string $code,
        string $description = ''
    ): PaymentMethodInterface {
        /** @var PaymentMethodInterface $paymentMethod */
        $paymentMethod = $this->paymentMethodExampleFactory->create([
            'name' => ucfirst($name),
            'code' => $code,
            'description' => $description,
            'gatewayName' => 'przelewy24',
            'gatewayFactory' => 'przelewy24',
            'enabled' => true,
            'channels' => $this->sharedStorage->has('channel') ? [$this->sharedStorage->get('channel')] : [],
        ]);

        $this->sharedStorage->set('payment_method', $paymentMethod);
        $this->paymentMethodRepository->add($paymentMethod);

        return $paymentMethod;
    }
}
