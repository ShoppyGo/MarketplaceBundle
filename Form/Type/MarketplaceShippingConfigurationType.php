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

use PrestaShop\PrestaShop\Adapter\Tax\TaxRuleDataProvider;
use PrestaShopBundle\Form\Admin\Type\CommonAbstractType;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class MarketplaceShippingConfigurationType extends CommonAbstractType
{
    protected MarketplaceCore $core;
    protected TranslatorInterface $translator;
    protected TaxRuleDataProvider $taxRuleDataProvider;
    /**
     * @var array|array[]
     */
    protected array $tax_rules_rates;

    public function __construct(
        MarketplaceCore $core,
        TranslatorInterface $translator,
        TaxRuleDataProvider $taxRuleDataProvider
    ) {
        $this->core = $core;
        $this->translator = $translator;
        $this->taxRuleDataProvider = $taxRuleDataProvider;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->tax_rules_rates = $this->taxRuleDataProvider->getTaxRulesGroupWithRates();
        $tax_rules = $this->formatDataChoicesList(
            $this->taxRuleDataProvider->getTaxRulesGroups(true),
            'id_tax_rules_group'
        );
        $builder->add(
            'carrier_name', TextType::class, [
                'label' => $this->translator->trans('Carrier name', [], 'Admin.Marketplace.Shipping'),
            ]
        )
            ->add(
                'from', MoneyType::class, [
                    'scale' => 2,
                    'label' => $this->translator->trans('from', [], 'Admin.Marketplace.Shipping'),
                ]
            )
            ->add(
                'to', MoneyType::class, [
                    'scale' => 2,
                    'label' => $this->translator->trans('to', [], 'Admin.Marketplace.Shipping'),
                ]
            )
            ->add(
                'cost', MoneyType::class, [
                    'scale' => 2,
                    'label' => $this->translator->trans('cost', [], 'Admin.Marketplace.Shipping'),
                ]
            )
            ->add(
                'id_tax_rules_group', ChoiceType::class, [
                    'choices' => $tax_rules,
                    'required' => true,
                    'choice_attr' => function ($val) {
                        return [
                            'data-rates' => implode(',', $this->tax_rules_rates[$val]['rates']),
                            'data-computation-method' => $this->tax_rules_rates[$val]['computation_method'],
                        ];
                    },
                    'attr' => [
                        'data-toggle' => 'select2',
                        'data-minimumResultsForSearch' => '7',
                    ],
                    'label' => $this->translator->trans('Tax rule', [], 'Admin.Catalog.Feature', []),
                ]
            )
        ;
        if ($this->core->isEmployStaff()) {
            $builder->add('id_seller', SellerChoiceType::class, [
                'label' => $this->translator->trans('seller', [], 'Admin.Marketplace.Shipping'),
            ]);
        }
    }
}
