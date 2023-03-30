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
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Exception\NotSellerException;
use ShoppyGo\MarketplaceBundle\Form\Widget\CategorySelectWidget;
use ShoppyGo\MarketplaceBundle\Form\Widget\CommissionSelectWidget;
use ShoppyGo\MarketplaceBundle\Provider\MarketplaceCommissionChoiceProvider;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceCategoryRepository;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerRepository;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Translation\TranslatorInterface;

class SupplierActionFormBuilderModifier extends AbstractHookListenerImplementation
{
    protected MarketplaceCore $core;
    protected MarketplaceCategoryRepository $categoryRepository;
    protected TranslatorInterface $translator;
    protected CategorySelectWidget $categorySelectWidget;
    protected CategoryDataProvider $categoryDataProvider;
    protected MarketplaceSellerRepository $marketplaceSellerCategoryRepository;
    protected CommissionSelectWidget $commissionSelectWidget;
    protected MarketplaceCommissionChoiceProvider $marketplaceCommissionChoiceProvider;

    public function __construct(
        MarketplaceCore $core,
        TranslatorInterface $translator,
        CategorySelectWidget $categorySelectWidget,
        CategoryDataProvider $categoryDataProvider,
        MarketplaceSellerRepository $marketplaceSellerCategoryRepository,
        CommissionSelectWidget $commissionSelectWidget,
        MarketplaceCommissionChoiceProvider $marketplaceCommissionChoiceProvider
    ) {
        $this->core = $core;
        $this->translator = $translator;
        $this->categorySelectWidget = $categorySelectWidget;
        $this->categoryDataProvider = $categoryDataProvider;
        $this->marketplaceSellerCategoryRepository = $marketplaceSellerCategoryRepository;
        $this->commissionSelectWidget = $commissionSelectWidget;
        $this->marketplaceCommissionChoiceProvider = $marketplaceCommissionChoiceProvider;
    }

    public function exec(array $params)
    {
        /** @var FormBuilder $form */
        $form = $params['form_builder'];

        if ($this->core->isEmployeeSeller() === true) {
            if ($this->core->getSellerId() !== (int) $params['id']) {
                throw new NotSellerException(
                    $this->translator->trans('You ar note authorized', [], 'Admin.Marketplace.Exception')
                );
            }
            $form->remove('is_enabled')
                ->remove('meta_title')
                ->remove('meta_description')
                ->remove('meta_keyword')
            ;

            return;
        }

        $id_seller = (int) $params['id'];

        $seller_category = $this->marketplaceSellerCategoryRepository->findSellerCategoryRootBy($id_seller);

        $root_category = $this->categoryDataProvider->getNestedCategories();

        $children_category = array_column(array_pop($root_category)['children'], 'name');
        $categoryChoices = [];
        $categoryChoices[''] = '';

        $categoryChoices = array_merge(
            $categoryChoices,
            array_combine(
                array_values($children_category),
                array_keys($children_category)
            )
        );

        $this->categorySelectWidget->setCategoryList(
            $categoryChoices
        )
            ->setDeafult($seller_category ? $seller_category->getIdCategory() : 0)
            ->addField($form)
        ;

        // TODO aggiungere il default commission
        $commissions = $this->marketplaceCommissionChoiceProvider->getChoices();
        $this->commissionSelectWidget->setCommissionList($commissions)
            ->setDeafult(1)
            ->addField($form)
        ;
    }
}
