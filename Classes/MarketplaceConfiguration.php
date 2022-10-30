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

namespace ShoppyGo\MarketplaceBundle\Classes;

class MarketplaceConfiguration
{
    const  CONFIGURATION_KEYS = [
//        'BWMARKETPLACE_CATEGORY_ID', # ok form
//        'BWMARKETPLACE_TAXRULE_SHIPPING',
//        'BWMARKETPLACE_PREFIX_SHIPPING_NAME',
    ];
    public static $adminMenu = [
        [
            'controller' => 'AdminMarketplace',
            'route_name' => '',
            'label' => 'Marketplace',
            'parent' => 'DEFAULT',
            'icon' => 'shopping_cart',
        ],
        [      #--- parent seller menu
            'controller' => 'AdminMarketplaceSeller',
            'route_name' => '',
            'label' => 'Your seller configuration',
            'parent' => 'DEFAULT',
            'icon' => 'settings_applications',
        ],
        [
            'controller' => 'AdminMarketplaceConfiguration',
            'route_name' => 'admin_marketplace_configuration',
            'label' => 'Configuration',
            'parent' => 'AdminMarketplace',
            'icon' => '',
        ],
        [
            'controller' => 'AdminMarketplaceSeller',
            'route_name' => 'admin_employees_index',
            'label' => 'Sellers',
            'parent' => 'AdminMarketplace',
            'icon' => '',
        ],
        #-------------------
        # submenu seller
        #
        [
            'controller' => 'AdminMarketplaceSellerShipping',
            'route_name' => 'admin_marketplace_seller_shipping',
            'label' => 'Configure shipping cost',
            'parent' => 'AdminMarketplaceSeller',
            'icon' => '',
        ],

    ];
    public static $hooks = [
        #------
        # seller
        #
        #  form
        'actionEmployeeFormBuilderModifier',
        #  data
        'actionAfterCreateEmployeeFormHandler',
        'actionAfterUpdateEmployeeFormHandler',
        'actionEmployeeGridDefinitionModifier',
        'actionEmployeeGridQueryBuilderModifier',
        #------
        #  categorie
        #
        #  form
        'actionCategoryFormBuilderModifier',
        #  data
        'actionAfterCreateCategoryFormHandler',
        'actionAfterUpdateCategoryFormHandler',
        'actionCategoryGridDefinitionModifier',
        'actionCategoryGridQueryBuilderModifier',
        #-------
        #  prodotti
        #
        #  lista
        'actionAdminProductsListingFieldsModifier',
        #  form
        'actionProductFormBuilderModifier',
        #  aggiunta e creazione
        #  @todo eliminare quando la pagina prodotto sarà passata a cqrs
        'actionObjectProductAddAfter',
        'actionObjectProductUpdateAfter',
        'actionAfterCreateProductFormHandler', //nuovi hook in attesa
        'actionAfterUpdateProductFormHandler',  //nuovi hook in attesa
        # lista prodotti
        'actionProductGridDefinitionModifier',
        'actionProductGridQueryBuilderModifier',
        #--------
        # carrello
        #
        'actionObjectCartUpdateBefore',
        'actionObjectCartUpdateAfter',
        'actionObjectProductInCartDeleteBefore',
        'actionObjectProductInCartDeleteAfter',
        #--------
        # ordini
        #
        'actionObjectOrderAddAfter',
        'actionObjectOrderDetailAddAfter',
        'actionObjectOrderCarrierAddAfter',
        'actionOrderStatusUpdate',
        'actionOrderGridQueryBuilderModifier',
        #-------
        #  altro
        #
        'header',
        'backOfficeHeader',

    ];
    #
    # autorizzioni per il seller
    #
    public static $authrole_for_seller = [
        'ROLE_MOD_TAB_ADMINADDRESSES_CREATE',
        'ROLE_MOD_TAB_ADMINADDRESSES_READ',
        'ROLE_MOD_TAB_ADMINADDRESSES_UPDATE',
        'ROLE_MOD_TAB_ADMINADDRESSES_DELETE',
        'ROLE_MOD_TAB_ADMINCATALOG_CREATE',
        'ROLE_MOD_TAB_ADMINCATALOG_READ',
        'ROLE_MOD_TAB_ADMINCATALOG_UPDATE',
        'ROLE_MOD_TAB_ADMINCATALOG_DELETE',
        'ROLE_MOD_TAB_ADMINORDERMESSAGE_CREATE',
        'ROLE_MOD_TAB_ADMINORDERMESSAGE_READ',
        'ROLE_MOD_TAB_ADMINORDERMESSAGE_UPDATE',
        'ROLE_MOD_TAB_ADMINORDERMESSAGE_DELETE',
        'ROLE_MOD_TAB_ADMINORDERS_CREATE',
        'ROLE_MOD_TAB_ADMINORDERS_READ',
        'ROLE_MOD_TAB_ADMINORDERS_UPDATE',
        'ROLE_MOD_TAB_ADMINORDERS_DELETE',
        'ROLE_MOD_TAB_ADMINPARENTCUSTOMER_CREATE',
        'ROLE_MOD_TAB_ADMINPARENTCUSTOMER_READ',
        'ROLE_MOD_TAB_ADMINPARENTCUSTOMER_UPDATE',
        'ROLE_MOD_TAB_ADMINPARENTCUSTOMER_DELETE',
        'ROLE_MOD_TAB_ADMINPARENTCUSTOMERTHREADS_CREATE',
        'ROLE_MOD_TAB_ADMINPARENTCUSTOMERTHREADS_READ',
        'ROLE_MOD_TAB_ADMINPARENTCUSTOMERTHREADS_UPDATE',
        'ROLE_MOD_TAB_ADMINPARENTCUSTOMERTHREADS_DELETE',
        'ROLE_MOD_TAB_ADMINPARENTMODULES_READ',
        'ROLE_MOD_TAB_ADMINPARENTMODULES_UPDATE',
        'ROLE_MOD_TAB_ADMINPARENTORDERS_CREATE',
        'ROLE_MOD_TAB_ADMINPARENTORDERS_READ',
        'ROLE_MOD_TAB_ADMINPARENTORDERS_UPDATE',
        'ROLE_MOD_TAB_ADMINPARENTORDERS_DELETE',
        'ROLE_MOD_TAB_ADMINPRODUCTS_CREATE',
        'ROLE_MOD_TAB_ADMINPRODUCTS_READ',
        'ROLE_MOD_TAB_ADMINPRODUCTS_UPDATE',
        'ROLE_MOD_TAB_ADMINPRODUCTS_DELETE',
        'ROLE_MOD_TAB_ADMINSUPPLYORDERS_CREATE',
        'ROLE_MOD_TAB_ADMINSUPPLYORDERS_READ',
        'ROLE_MOD_TAB_ADMINSUPPLYORDERS_UPDATE',
        'ROLE_MOD_TAB_ADMINSUPPLYORDERS_DELETE',
        'ROLE_MOD_TAB_SELL_CREATE',
        'ROLE_MOD_TAB_SELL_READ',
        'ROLE_MOD_TAB_SELL_UPDATE',
        'ROLE_MOD_TAB_SELL_DELETE',
        'ROLE_MOD_TAB_ADMINMARKETPLACE_CREATE',
        'ROLE_MOD_TAB_ADMINMARKETPLACE_READ',
        'ROLE_MOD_TAB_ADMINMARKETPLACE_UPDATE',
        'ROLE_MOD_TAB_ADMINMARKETPLACE_DELETE',
        'ROLE_MOD_TAB_ADMINMARKETPLACESELLER_CREATE',
        'ROLE_MOD_TAB_ADMINMARKETPLACESELLER_READ',
        'ROLE_MOD_TAB_ADMINMARKETPLACESELLER_UPDATE',
        'ROLE_MOD_TAB_ADMINMARKETPLACESELLER_DELETE',
        'ROLE_MOD_TAB_ADMINMARKETPLACECONFIGURATION_CREATE',
        'ROLE_MOD_TAB_ADMINMARKETPLACECONFIGURATION_READ',
        'ROLE_MOD_TAB_ADMINMARKETPLACECONFIGURATION_UPDATE',
        'ROLE_MOD_TAB_ADMINMARKETPLACECONFIGURATION_DELETE',
        'ROLE_MOD_TAB_ADMINMARKETPLACESELLERSHIPPING_CREATE',
        'ROLE_MOD_TAB_ADMINMARKETPLACESELLERSHIPPING_READ',
        'ROLE_MOD_TAB_ADMINMARKETPLACESELLERSHIPPING_UPDATE',
        'ROLE_MOD_TAB_ADMINMARKETPLACESELLERSHIPPING_DELETE',

    ];

    #
    # moduli per seller
    #
    public static $modauthrole_for_seller = [
        'ROLE_MOD_MODULE_BWMARKETPLACE_READ',
        'ROLE_MOD_MODULE_BWMARKETPLACE_UPDATE',
    ];
}
