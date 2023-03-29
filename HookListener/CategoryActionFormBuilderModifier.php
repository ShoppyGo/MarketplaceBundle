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

use PrestaShop\PrestaShop\ShoppyGo\MarketplaceBundle\Exception\NotSellerException;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Form\Widget\SellerSwitchWidget;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceCategoryRepository;
use Symfony\Component\Form\FormBuilder;

class CategoryActionFormBuilderModifier extends AbstractHookListenerImplementation
{
    protected MarketplaceCore $core;
    protected MarketplaceCategoryRepository $categoryRepository;
    protected SellerSwitchWidget $sellerSwitchWidget;

    public function __construct(
        MarketplaceCore $core,
        MarketplaceCategoryRepository $categoryRepository,
        SellerSwitchWidget $sellerSwitchWidget
    ) {
        $this->core = $core;
        $this->categoryRepository = $categoryRepository;
        $this->sellerSwitchWidget = $sellerSwitchWidget;
    }

    public function exec(array $params)
    {
        if ($this->core->isEmployeeSeller()) {
            throw new NotSellerException('Seller can\'t update category');
        }
        /** @var FormBuilder $form */
        $form = $params['form_builder'];
        $id_category = $params['id'];

        $category = $this->categoryRepository->findOneByShop($id_category);
        $is_seller = $category && $category->isSeller();
        $this->sellerSwitchWidget->setSeller($is_seller)
            ->addField($form)
        ;
    }
}
