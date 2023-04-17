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

namespace ShoppyGo\MarketplaceBundle\Form\Widget;

use ShoppyGo\MarketplaceBundle\Entity\MarketplaceEmployeeSeller;
use ShoppyGo\MarketplaceBundle\Provider\SellerChoiceProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilder;

class SellerSelectWidget
{
    protected SellerChoiceProvider $sellerChoiceProvider;
    protected ?MarketplaceEmployeeSeller $seller;

    public function __construct(SellerChoiceProvider $sellerChoiceProvider)
    {
        $this->sellerChoiceProvider = $sellerChoiceProvider;
    }

    public function addField(FormBuilder $form, $required = true): void
    {
        $form->add(
            'supplier', ChoiceType::class, [
                'label' => 'Seller',
                'choices' => $this->sellerChoiceProvider->getChoices(),
                'data' => $this->seller ? $this->seller->getIdSeller() : '',
                'required' => $required,
            ]
        );
    }

    public function setSeller(?MarketplaceEmployeeSeller $seller): void
    {
        $this->seller = $seller;
    }
}
