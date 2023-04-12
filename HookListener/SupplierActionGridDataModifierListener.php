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

use PrestaShop\PrestaShop\Core\Grid\Data\GridData;
use PrestaShop\PrestaShop\Core\Grid\Record\RecordCollection;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use Symfony\Component\Translation\TranslatorInterface;

class SupplierActionGridDataModifierListener extends AbstractHookListenerImplementation
{
    protected MarketplaceCore $core;
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
        /** @var GridData $data */
        $data = $params['data'];
        $collection = new RecordCollection([]);
        foreach ($data->getRecords() as $record) {
            if ((int) $record['id_supplier'] === (int) $this->core->getSellerId()) {
                $collection = new RecordCollection([$record]);
                break;
            }
        }

        $params['data'] = new GridData($collection, 1);
    }
}
