<?php
/**
 * Copyright since 2022 Bwlab of Luigi Massa and Contributors
 * Bwlab of Luigi Massa is an Italy Company
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.md.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/OSL-3.0
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@shoppygo.io so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade ShoppyGo to newer
 * versions in the future. If you wish to customize ShoppyGo for your
 * needs please refer to https://docs.shoppygo.io/ for more information.
 *
 * @author    Bwlab and Contributors <contact@shoppygo.io>
 * @copyright Since 2022 Bwlab of Luigi Massa and Contributors
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */

namespace ShoppyGo\MarketplaceBundle\Form\Type;

use PrestaShopBundle\Form\Admin\Type\SwitchType;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceCommission;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MarketplaceCommissionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('commissionName', TextType::class, [
                'label' => 'Nome commissione',
                'required' => true,
            ])
            ->add('fixedCommission', MoneyType::class, [
                'label' => 'Commissione fissa',
                'currency' => 'EUR',
                'required' => false,
            ])
            ->add('commissionPercentage', PercentType::class, [
                'label' => 'Commissione percentuale',
                'type' => 'fractional',
                'required' => false,
            ])
            ->add('totalProductsNetOfVat', SwitchType::class, [
                'label' => 'Totale prodotti al netto dell\'iva',
                'required' => false,
            ])
            ->add('totalNetOfDiscount', SwitchType::class, [
                'label' => 'Totale al netto dello sconto',
                'required' => false,
            ])
            ->add('shippingValueNetOfVat', SwitchType::class, [
                'label' => 'Valore spedizione al netto dell\'iva',
                'required' => false,
            ])
            ->add('totalVat', SwitchType::class, [
                'label' => 'Totale iva',
                'required' => false,
            ])
            ->add('totalGeneral', SwitchType::class, [
                'label' => 'Totale generale',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => MarketplaceCommission::class,
        ]);
    }
}
