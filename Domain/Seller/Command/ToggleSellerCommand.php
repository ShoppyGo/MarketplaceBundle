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

namespace ShoppyGo\MarketplaceBundle\Domain\Seller\Command;


use Context;
use ShoppyGo\MarketplaceBundle\Adapter\Entity\Seller;

/**
 * Adds new address
 */
class ToggleSellerCommand
{
    protected bool $switch;
    private int $id;
    private string $fieldName;
    private string $entityName;

    public function __construct(int $id,string $fieldName,string $entityName, bool $switch)
    {
        $this->id = $id;
        $this->fieldName = $fieldName;
        $this->entityName = $entityName;
        $this->switch = $switch;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getFieldName(): string
    {
        return $this->fieldName;
    }

    public function getSeller(): ?Seller
    {
        return $this->seller;
    }

    public function getEntityName(): string
    {
        return $this->entityName;
    }

    /**
     * @return bool
     */
    public function getSwitch(): bool
    {
        return $this->switch;
    }

}
