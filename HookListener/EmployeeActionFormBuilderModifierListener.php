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

namespace ShoppyGo\MarketplaceBundle\HookListener;

use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceEmployeeSeller;
use ShoppyGo\MarketplaceBundle\Form\Widget\SellerSelectWidget;
use ShoppyGo\MarketplaceBundle\Form\Widget\SellerSwitchWidget;
use ShoppyGo\MarketplaceBundle\Provider\SellerChoiceProvider;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceEmployeeSellerRepository;

class EmployeeActionFormBuilderModifierListener extends AbstractHookListenerImplementation
{
    protected SellerChoiceProvider $sellerChoiceProvider;
    protected MarketplaceCore $core;
    protected MarketplaceEmployeeSellerRepository $employeeSellerRepository;
    protected SellerSelectWidget $sellerSelectWidget;
    protected SellerSwitchWidget $sellerSwitchWidget;

    public function __construct(
        SellerChoiceProvider $sellerChoiceProvider,
        MarketplaceCore $core,
        MarketplaceEmployeeSellerRepository $employeeSellerRepository
    ) {
        $this->core = $core;
        $this->employeeSellerRepository = $employeeSellerRepository;
        $this->sellerChoiceProvider = $sellerChoiceProvider;
    }

    public function exec(array $params)
    {
        if ($this->core->isEmployStaff() === false) {
            return;
        }
        $id_employee = $params['id'];
        $form = $params['form_builder'];

        if ($id_employee === $this->core->getEmployee()->id && $this->core->isEmployStaff() === true) {
            return;
        }

        /** @var MarketplaceEmployeeSeller $seller */
        $seller = $this->employeeSellerRepository->findSellerByEmployee($id_employee);

        $this->sellerSelectWidget->setSeller($seller);

        $this->sellerSelectWidget->addField($form, false);
    }

    public function setSellerSelectWidget(SellerSelectWidget $sellerSelectWidget): void
    {
        $this->sellerSelectWidget = $sellerSelectWidget;
    }
}
