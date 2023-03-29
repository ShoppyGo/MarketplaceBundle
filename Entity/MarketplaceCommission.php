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

namespace ShoppyGo\MarketplaceBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ShoppyGo\MarketplaceBundle\Repository\MarketplaceCommissionRepository")
 */
class MarketplaceCommission
{
    /**
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue()
     * @ORM\Column(name="id_marketplace_commission", type="integer")
     */
    private int $id;
    /**
     * @var string
     * @ORM\Column(name="commission_name", type="string", length=255)
     */
    private string $commissionName;
    /**
     * @var float
     * @ORM\Column(name="fixed_commission", type="float")
     */
    private float $fixedCommission = 0;
    /**
     * @var float
     * @ORM\Column(name="commission_percentage", type="float")
     */
    private float $commissionPercentage = 0;
    /**
     * @var bool
     * @ORM\Column(name="total_products_net_of_vat", type="boolean")
     */
    private bool $totalProductsNetOfVat = false;
    /**
     * @var bool
     * @ORM\Column(name="total_net_of_discount", type="boolean")
     */
    private bool $totalNetOfDiscount = false;
    /**
     * @var bool
     * @ORM\Column(name="shipping_value_net_of_vat", type="boolean")
     */
    private bool $shippingValueNetOfVat = false;
    /**
     * @var bool
     * @ORM\Column(name="total_vat", type="boolean")
     */
    private bool $totalVat = false;
    /**
     * @var bool
     * @ORM\Column(name="total_general", type="boolean")
     */
    private bool $totalGeneral = false;

    public function __toString(): string
    {
        return $this->getCommissionName();
    }

    /**
     * @return string
     */
    public function getCommissionName(): string
    {
        return $this->commissionName;
    }

    /**
     * @param string $commissionName
     */
    public function setCommissionName(string $commissionName): self
    {
        $this->commissionName = $commissionName;

        return $this;
    }

    /**
     * @return float
     */
    public function getCommissionPercentage(): float
    {
        return $this->commissionPercentage;
    }

    /**
     * @param float $commissionPercentage
     */
    public function setCommissionPercentage(float $commissionPercentage): self
    {
        $this->commissionPercentage = $commissionPercentage;

        return $this;
    }

    /**
     * @return float
     */
    public function getFixedCommission(): float
    {
        return $this->fixedCommission;
    }

    /**
     * @param float $fixedCommission
     */
    public function setFixedCommission(float $fixedCommission): self
    {
        $this->fixedCommission = $fixedCommission;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isShippingValueNetOfVat(): bool
    {
        return $this->shippingValueNetOfVat;
    }

    /**
     * @param bool $shippingValueNetOfVat
     */
    public function setShippingValueNetOfVat(bool $shippingValueNetOfVat): self
    {
        $this->shippingValueNetOfVat = $shippingValueNetOfVat;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTotalGeneral(): bool
    {
        return $this->totalGeneral;
    }

    /**
     * @param bool $totalGeneral
     */
    public function setTotalGeneral(bool $totalGeneral): self
    {
        $this->totalGeneral = $totalGeneral;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTotalNetOfDiscount(): bool
    {
        return $this->totalNetOfDiscount;
    }

    /**
     * @param bool $totalNetOfDiscount
     */
    public function setTotalNetOfDiscount(bool $totalNetOfDiscount): self
    {
        $this->totalNetOfDiscount = $totalNetOfDiscount;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTotalProductsNetOfVat(): bool
    {
        return $this->totalProductsNetOfVat;
    }

    /**
     * @param bool $totalProductsNetOfVat
     */
    public function setTotalProductsNetOfVat(bool $totalProductsNetOfVat): self
    {
        $this->totalProductsNetOfVat = $totalProductsNetOfVat;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTotalVat(): bool
    {
        return $this->totalVat;
    }

    /**
     * @param bool $totalVat
     */
    public function setTotalVat(bool $totalVat): self
    {
        $this->totalVat = $totalVat;

        return $this;
    }
}
