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
 * @ORM\Entity(repositoryClass="ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerOrderRepository")
 *
 * Class MarketPlaceSeller
 */
class MarketplaceSellerOrder
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_order", type="integer")
     *
     * @var int
     */
    private int $id_order;
    /**
     * @ORM\Column(name="id_supplier", type="integer")
     *
     * @var int
     */
    private int $id_seller;
    /**
     * @ORM\Column(name="id_order_main", type="integer")
     *
     * @var int
     */
    private int $id_order_main;
    /**
     * @ORM\Column(name="split", type="boolean")
     */
    private bool $split = false;

    public function getIdOrder(): int
    {
        return $this->id_order;
    }

    public function setIdOrder(int $id_order): self
    {
        $this->id_order = $id_order;

        return $this;
    }

    public function getIdOrderMain(): int
    {
        return $this->id_order_main;
    }

    public function setIdOrderMain(int $id_order_main): self
    {
        $this->id_order_main = $id_order_main;

        return $this;
    }

    public function getIdSeller(): int
    {
        return $this->id_seller;
    }

    public function setIdSeller(int $id_seller): self
    {
        $this->id_seller = $id_seller;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSplit(): bool
    {
        return $this->split;
    }

    /**
     * @param bool $split
     *
     * @return void
     */
    public function setSplit(bool $split): self
    {
        $this->split = $split;

        return $this;
    }
}
