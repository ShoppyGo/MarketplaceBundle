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
 * @ORM\Entity(repositoryClass="ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerShippingRepository")
 */
class MarketplaceSellerShipping
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id_shipping", type="integer")
     *
     * @var int
     */
    private int $id_shipping;

    /**
     * @ORM\Column(name="id_supplier", type="integer")
     *
     * @var int
     */
    private ?int $id_seller;
    /**
     * @ORM\Column(name="carrier_name", type="string", length=255)
     * @var string
     */
    private string $carrier_name;
    /**
     * @ORM\Column(name="from_total", type="decimal", precision=2)
     *
     * @var float
     */
    private float $from = 0;
    /**
     * @ORM\Column(name="to_total", type="decimal", precision=2)
     *
     * @var float
     */
    private float $to = 0;
    /**
     * @ORM\Column(name="shipping_cost", type="decimal", precision=2)
     *
     * @var float
     */
    private float $cost = 0;

    /**
     * @ORM\Column(name="id_tax_rules_group", type="integer")
     * @var int
     */
    private int $id_tax_rules_group;

    public function getIdTaxRulesGroup(): int
    {
        return $this->id_tax_rules_group;
    }

    public function setIdTaxRulesGroup(int $id_tax_rules_group): self
    {
        $this->id_tax_rules_group = $id_tax_rules_group;
        return $this;
    }

    /**
     * @return string
     */
    public function getCarrierName(): string
    {
        return $this->carrier_name;
    }

    /**
     * @param string $carrier_name
     * @return $this
     */
    public function setCarrierName(string $carrier_name): self
    {
        $this->carrier_name = $carrier_name;

        return $this;
    }

    public function getCost()
    {
        return $this->cost;
    }

    public function setCost($cost): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function getFrom()
    {
        return $this->from;
    }

    public function setFrom($from): self
    {
        $this->from = $from;

        return $this;
    }

    public function getIdSeller(): ?int
    {
        return $this->id_seller;
    }

    public function setIdSeller(int $id_seller): self
    {
        $this->id_seller = $id_seller;

        return $this;
    }

    public function getIdShipping(): int
    {
        return $this->id_shipping;
    }

    public function setIdShipping(int $id_shipping): self
    {
        $this->id_shipping = $id_shipping;

        return $this;
    }

    public function getTo()
    {
        return $this->to;
    }

    public function setTo($to): self
    {
        $this->to = $to;

        return $this;
    }

}
