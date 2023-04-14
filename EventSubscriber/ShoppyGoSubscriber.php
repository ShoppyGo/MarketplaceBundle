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

namespace ShoppyGo\MarketplaceBundle\EventSubscriber;

use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ShoppyGoSubscriber implements EventSubscriberInterface
{
    /**
     * @var Container
     */
    protected $container;
    /**
     * @var iterable
     */
    protected $hooks;
    protected LoggerInterface $logger;

    public function __construct(iterable $hooks, LoggerInterface $logger)
    {
        $this->hooks = $hooks;
        $this->logger = $logger;
    }

    public function __call($hookname, $event)
    {
        // vedi LegacyHookSubscriber __call
        foreach ($this->hooks as $key => $hook) {
            $this->logger->info('SHOPPYGO-SUBSCRBER: ' . $hookname);
            if (true === $hook->supports($hookname)) {
                $event_hook = $event[0];

                return $hook->exec($event_hook->getHookParameters());
            }
        }
    }

    public static function getSubscribedEvents(): array
    {
        $hooks = [
            'actionEmployeeFormBuilderModifier' => 1,
            'actionAfterCreateEmployeeFormHandler' => 1,
            'actionAfterUpdateEmployeeFormHandler' => 1,
            'actionEmployeeGridDefinitionModifier' => 1,
            'actionEmployeeGridQueryBuilderModifier' => 1,
            'actionAdminProductsListingFieldsModifier' => 1,
            'actionCategoryFormBuilderModifier' => 1,
            'actionAfterUpdateCategoryFormHandler' => 1,
            'actionAfterCreateCategoryFormHandler' => 1,
            'actionCategoryGridDefinitionModifier' => 1,
            'actionCategoryGridQueryBuilderModifier' => 1,
            'actionSupplierFormBuilderModifier' => 1,
            'actionAfterUpdateSupplierFormHandler' => 1,
            'actionAfterCreateSupplierFormHandler' => 1,
            'actionOrderGridQueryBuilderModifier' => 1,
            'actionOrderGridDefinitionModifier' => 1,
            'actionOrderGridDataModifier' => 1,
            'actionAdminStatusesFormModifier' => 1,
            'marketPlaceAdminStatusOrderPostProcess' => 1,
            'actionAdminStatusesListingFieldsModifier' => 1,
            'actionAdminStatusesListingResultsModifier' => 1,
            'actionSupplierGridDefinitionModifier' => 1,
            'actionSupplierGridDataModifier' => 1,
        ];
        $hook_list = [];
        foreach ($hooks as $hook => $params) {
            $hook_list[strtolower($hook)] = [
                strtolower($hook),
                $params,
            ];
        }

        return $hook_list;
    }
}
