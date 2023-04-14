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

use Doctrine\ORM\QueryBuilder;
use PrestaShop\PrestaShop\Core\Grid\Data\GridData;
use PrestaShop\PrestaShop\Core\Grid\Record\RecordCollection;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceCore;
use ShoppyGo\MarketplaceBundle\Engine\CalculatorEngine;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceSeller;
use ShoppyGo\MarketplaceBundle\Mapper\OrderMapper;
use ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerRepository;
use Symfony\Contracts\Translation\TranslatorInterface;

class OrderActionGridDataModifier extends AbstractHookListenerImplementation
{
    protected MarketplaceCore $core;
    protected TranslatorInterface $translator;
    protected MarketplaceSellerRepository $marketplaceSellerRepository;

    public function __construct(
        MarketplaceCore $core,
        TranslatorInterface $translator,
        MarketplaceSellerRepository $marketplaceSellerRepository
    ) {
        $this->core = $core;
        $this->translator = $translator;
        $this->marketplaceSellerRepository = $marketplaceSellerRepository;
    }

    public function exec(array $params): void
    {
        /** @var RecordCollection $records */
        $records = $params['data']->getRecords()
            ->all()
        ;
        foreach ($records as &$record) {
            $id_order = $record['id_order'];
            $mkt_seller = $this->marketplaceSellerRepository->findOneBy(['id_seller' => $record['id_seller']]);
            if(null === $mkt_seller) {
                continue;
            }
            $record['commission_amount'] = (new CalculatorEngine())->calculateCommission(
                OrderMapper::mapArrayToMarketplaceOrderCommissionDTO($record),
                $mkt_seller->getMarketplaceCommission()
            );
        }

        $params['data'] = new GridData(new RecordCollection($records), count($records));
    }
}
