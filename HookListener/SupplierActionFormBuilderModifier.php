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

use PrestaShop\PrestaShop\Adapter\Category\CategoryDataProvider;
use PrestaShop\PrestaShop\ShoppyGo\MarketplaceBundle\Exception\NotSellerException;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Form\Widget\CategorySelectWidget;
use ShoppyGo\MarketplaceBundle\Form\Widget\CommissionSelectWidget;
use ShoppyGo\MarketplaceBundle\Form\Widget\SellerSwitchWidget;
use ShoppyGo\MarketplaceBundle\Provider\Category\MarketplaceCategoryDataProvider;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceCategoryRepository;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerCategoryRepository;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Translation\TranslatorInterface;

class SupplierActionFormBuilderModifier extends AbstractHookListenerImplementation
{
    protected MarketplaceCore $core;
    protected MarketplaceCategoryRepository $categoryRepository;
    protected TranslatorInterface $translator;
    protected CategorySelectWidget $categorySelectWidget;
    protected CategoryDataProvider $categoryDataProvider;
    protected MarketplaceSellerCategoryRepository $marketplaceSellerCategoryRepository;
    protected CommissionSelectWidget $commissionSelectWidget;

    public function __construct(
        MarketplaceCore $core,
        TranslatorInterface $translator,
        CategorySelectWidget $categorySelectWidget,
        CategoryDataProvider $categoryDataProvider,
        MarketplaceSellerCategoryRepository $marketplaceSellerCategoryRepository,
        CommissionSelectWidget $commissionSelectWidget,
    ) {
        $this->core = $core;
        $this->translator = $translator;
        $this->categorySelectWidget = $categorySelectWidget;
        $this->categoryDataProvider = $categoryDataProvider;
        $this->marketplaceSellerCategoryRepository = $marketplaceSellerCategoryRepository;
        $this->commissionSelectWidget = $commissionSelectWidget;
    }

    public function exec(array $params)
    {
        if ($this->core->isEmployeeSeller()) {
            throw new NotSellerException('Seller can\'t update');
        }
        /** @var FormBuilder $form */
        $form = $params['form_builder'];
        $id_seller = (int)$params['id'];

        $seller_category = $this->marketplaceSellerCategoryRepository->findSellerCategoryRootBy($id_seller);

        $root_category = $this->categoryDataProvider->getNestedCategories();

        $children_category =array_column(array_pop($root_category)['children'],'name');

        $this->categorySelectWidget->setCategoryList(
            array_combine(array_values($children_category), array_keys($children_category))
        )
            ->setDeafult($seller_category ? $seller_category->getIdCategory() : 0)
            ->addField($form)
        ;

        //TODO comoletare con il provider per le commissioni
        $commissions = [];
        $this->commissionSelectWidget->setCommissionList($commissions)
            ->setDeafult(1)
            ->addField($form);
    }

}
