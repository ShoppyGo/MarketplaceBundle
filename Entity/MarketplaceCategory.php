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
 * @ORM\Entity(repositoryClass="ShoppyGo\MarketplaceBundle\Repository\MarketplaceCategoryRepository")
 *
 * Class MarketplaceCategory
 */
class MarketplaceCategory implements MarketplaceEntityInterface
{
    /**
     * @Orm\Id
     * @ORM\Column(name="id_category", type="integer")
     *
     * @var int
     */
    private $id_category;
    /**
     * @ORM\Column(name="seller", type="boolean")
     *
     * @var bool
     */
    private $seller = false;

    public function getId()
    {
        return $this->getIdCategory();
    }

    /**
     * @return int
     */
    public function getIdCategory(): int
    {
        return $this->id_category;
    }

    /**
     * @param int $id_category
     */
    public function setIdCategory(int $id_category): self
    {
        $this->id_category = $id_category;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSeller(): bool
    {
        return $this->seller;
    }

    /**
     * @param bool $seller
     */
    public function setSeller(bool $seller): self
    {
        $this->seller = $seller;

        return $this;
    }

    public function setId($id)
    {
        $this->setIdCategory(id);
    }

    public function toggleSeller()
    {
        $this->setSeller(!$this->isSeller());
    }
}
