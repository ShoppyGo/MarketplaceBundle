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
use ShoppyGo\MarketplaceBundle\Interfaces\MarketplaceEntityInterface;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerOrderStatusRepository")
 */
class MarketplaceSellerOrderStatus implements MarketplaceEntityInterface
{
    /**
     * @Orm\Id
     * @ORM\Column(name="id_order_state", type="integer")
     *
     * @var int
     */
    private $id_order_state;
    /**
     * @ORM\Column(name="seller", type="boolean")
     *
     * @var bool
     */
    private $seller = false;

    public function getId()
    {
        return $this->getIdOrderState();
    }

    /**
     * @return int
     */
    public function getIdOrderState(): int
    {
        return $this->id_order_state;
    }

    /**
     * @param int $id_order_status
     */
    public function setIdOrderState(int $id_order_state): void
    {
        $this->id_order_state = $id_order_state;
    }

    public function isSeller()
    {
        return $this->seller;
    }

    public function setSeller(bool $seller)
    {
        $this->seller = $seller;
    }

    public function setId($id)
    {
        $this->id_order_status = $id;
    }

    public function toggleSeller()
    {
        $this->setSeller(!$this->seller);
    }
}
