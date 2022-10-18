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

use PrestaShop\PrestaShop\Core\Form\IdentifiableObject\Builder\FormBuilderInterface;
use PrestaShop\PrestaShop\Core\Grid\Column\ColumnCollection;
use PrestaShop\PrestaShop\Core\Grid\Column\Type\DataColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderActionGridDefinitionModifierListener extends AbstractHookListenerImplementation
{
    protected MarketplaceCore $core;
    protected FormBuilderInterface $formBuilder;
    protected TranslatorInterface $translator;

    public function __construct(
        MarketplaceCore $core,
        TranslatorInterface $translator
    ) {
        $this->core = $core;
        $this->translator = $translator;
    }

    public function exec(array $params)
    {
        if (false === $this->core->isEmployStaff()) {
            return;
        }
        /** @var GridDefinitionInterface $definition */
        $definition = $params['definition'];
        /** @var ColumnCollection $columns */
        $columns = $definition->getColumns();
        //-------creo la colonna e la nomino is_seller
        $seller_name = new DataColumn('seller_name');
        $seller_name->setName($this->translator->trans('Seller name', [], 'Admin.Shoppygo.Marketplace'))
            ->setOptions(['field' => 'seller_name'])
        ;

        //-------creo la colonna e la nomino is_seller
//        $seller_name = new DataColumn('split');
//        $seller_name->setName($this->translator->trans('Split', [], 'Admin.Shoppygo.Marketplace'));
////            ->setOptions();

        //----aggiungo la colonna alla grid
        $columns->addAfter('reference', $seller_name);
    }
}
