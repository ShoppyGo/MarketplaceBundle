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
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSeller;
use ShoppyGo\MarketplaceBundle\Exception\NotSellerException;
use ShoppyGo\MarketplaceBundle\Form\Widget\CategorySelectWidget;
use ShoppyGo\MarketplaceBundle\Form\Widget\CommissionSelectWidget;
use ShoppyGo\MarketplaceBundle\Provider\MarketplaceCommissionChoiceProvider;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceCategoryRepository;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerRepository;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Translation\TranslatorInterface;

class SupplierActionFormBuilderModifier extends AbstractHookListenerImplementation
{
    protected MarketplaceCore $core;
    protected MarketplaceCategoryRepository $categoryRepository;
    protected TranslatorInterface $translator;
    protected CategorySelectWidget $categorySelectWidget;
    protected CategoryDataProvider $categoryDataProvider;
    protected MarketplaceSellerRepository $marketplaceSellerRepository;
    protected CommissionSelectWidget $commissionSelectWidget;
    protected MarketplaceCommissionChoiceProvider $marketplaceCommissionChoiceProvider;

    public function __construct(
        MarketplaceCore $core,
        TranslatorInterface $translator,
        CategorySelectWidget $categorySelectWidget,
        CategoryDataProvider $categoryDataProvider,
        MarketplaceSellerRepository $marketplaceSellerRepository,
        CommissionSelectWidget $commissionSelectWidget,
        MarketplaceCommissionChoiceProvider $marketplaceCommissionChoiceProvider
    ) {
        $this->core = $core;
        $this->translator = $translator;
        $this->categorySelectWidget = $categorySelectWidget;
        $this->categoryDataProvider = $categoryDataProvider;
        $this->marketplaceSellerRepository = $marketplaceSellerRepository;
        $this->commissionSelectWidget = $commissionSelectWidget;
        $this->marketplaceCommissionChoiceProvider = $marketplaceCommissionChoiceProvider;
    }

    public function exec(array $params)
    {
        $id_supplier = (int)$params['id'];
        /** @var MarketplaceSeller $marketplace_seller */
        $marketplace_seller = $this->marketplaceSellerRepository->findOneBy(['id_seller' => $id_supplier]);

        /** @var FormBuilder $form */
        $form = $params['form_builder'];
        $form->add('vat_number', TextType::class, [
            'required' => true,
            'label'    => $this->translator->trans('Vat', [], 'Admin.Marketplace.Label'),
            'data'=>$marketplace_seller?->getVatNumber()
        ])
            ->add('website', UrlType::class, [
                'required' => true,
                'label'    => $this->translator->trans('Web site', [], 'Admin.Marketplace.Label'),
                'data'=>$marketplace_seller?->getWebsite()
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label'    => $this->translator->trans('Email', [], 'Admin.Marketplace.Label'),
                'data'=>$marketplace_seller?->getEmail()
            ])
            ->add('return_policy', TextareaType::class, [
                'required' => true,
                'label'    => $this->translator->trans('Return policy', [], 'Admin.Marketplace.Label'),
                'data'=>$marketplace_seller?->getReturnPolicy()
            ])
        ;;
        if ($this->core->isEmployeeSeller() === true) {
            if ($this->core->getSellerId() !== $id_supplier) {
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

        $id_seller = $id_supplier;

        $seller_category = $this->marketplaceSellerRepository->findSellerCategoryRootBy($id_seller);

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

        $commissions = $this->marketplaceCommissionChoiceProvider->getChoices();
        $this->commissionSelectWidget->setCommissionList($commissions)
            ->setDeafult(1) // TODO aggiungere il default commission
            ->addField($form)
        ;
    }
}
