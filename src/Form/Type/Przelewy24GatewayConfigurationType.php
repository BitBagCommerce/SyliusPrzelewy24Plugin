<?php

/*
 * This file has been created by developers from BitBag.
 * Feel free to contact us once you face any issues or want to start
 * another great project.
 * You can find more information about us on https://bitbag.shop and write us
 * an email on mikolaj.krol@bitbag.pl.
 */

declare(strict_types=1);

namespace BitBag\SyliusPrzelewy24Plugin\Form\Type;

use BitBag\SyliusPrzelewy24Plugin\Bridge\Przelewy24BridgeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

final class Przelewy24GatewayConfigurationType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('merchant_id', TextType::class, [
                'label' => 'bitbag_sylius_przelewy24_plugin.ui.merchant_id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_przelewy24_plugin.merchant_id.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('crc_key', TextType::class, [
                'label' => 'bitbag_sylius_przelewy24_plugin.ui.crc_key',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_przelewy24_plugin.crc_key.not_blank',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('environment', ChoiceType::class, [
                'choices' => [
                    'bitbag_sylius_przelewy24_plugin.ui.sandbox' => Przelewy24BridgeInterface::SANDBOX_ENVIRONMENT,
                    'bitbag_sylius_przelewy24_plugin.ui.production' => Przelewy24BridgeInterface::PRODUCTION_ENVIRONMENT,
                ],
                'label' => 'bitbag_sylius_przelewy24_plugin.ui.environment',
                'constraints' => [
                    new NotBlank([
                        'message' => 'bitbag_sylius_przelewy24_plugin.environment.not_blank',
                        'groups' => ['sylius'],
                    ])
                ],
            ])
        ;
    }
}
