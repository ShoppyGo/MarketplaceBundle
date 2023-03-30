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
use PrestaShop\PrestaShop\Core\Grid\Column\Type\Common\ToggleColumn;
use PrestaShop\PrestaShop\Core\Grid\Definition\GridDefinitionInterface;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use Symfony\Component\Translation\TranslatorInterface;

class SupplierActionGridDefinitionModifierListener extends AbstractHookListenerImplementation
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
        if (true === $this->core->isEmployStaff()) {
            return;
        }
        /** @var GridDefinitionInterface $definition */
        $definition = $params['definition'];
        /** @var ColumnCollection $columns */
        $columns = $definition->getColumns();
        $columns->remove('bulk');
        $columns->remove('id_supplier');
        $columns->remove('logo');
        $columns->remove('active');
        $definition->getFilters()->remove('id_supplier');
        $definition->getFilters()->remove('active');
//        //-------creo la colonna e la nomino is_seller
//        $toggle_seller = new ToggleColumn('is_seller');
//
//        $route = 'admin_marketplace_category_toggle_seller';
//        $route_parama_name = 'id';
//        $primary_field = 'id_category';
//
//        $toggle_seller->setName($this->translator->trans('Is seller'))
//            ->setOptions(
//                [
//                    'field' => 'is_seller',
//                    'primary_field' => $primary_field,
//                    //                    #------controller richiamato al click dell'utente
//                    'route' => $route,
//                    //                    #-----parametro della route che sarà modificato con il primary_field
//                    'route_param_name' => $route_parama_name,     //passa sempre il primary_field
//                ]
//            )
//        ;
//
//        //----aggiungo la colonna alla grid
//        $columns->addAfter('active', $toggle_seller);
    }
}
