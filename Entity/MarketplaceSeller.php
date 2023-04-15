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
//use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="ShoppyGo\MarketplaceBundle\Repository\MarketplaceSellerRepository")
 *
 * Class MarketplaceSellerCategory
 */
class MarketplaceSeller
{
    /**
     * @ORM\Id
     * @ORM\Column(name="id_supplier", type="integer")
     *
     * @var int
     */
    private int $id_seller;

    /**
     * @ORM\Column(name="id_category", type="integer")
     *
     * @var int
     */
    private int $id_category;

    /**
     * @ORM\ManyToOne(targetEntity="ShoppyGo\MarketplaceBundle\Entity\MarketplaceCommission",
     *     inversedBy="id_marketplace_commission")
     * @ORM\JoinColumn(name="id_marketplace_commission", referencedColumnName="id_marketplace_commission")
     */
    private MarketplaceCommission $marketplaceCommission;

    /**
     * @ORM\Column(name="vat_number", type="string", length=25)
     *
     * @var string
     */
    private string $vat_number;
    /**
     * @var string
     * @ORM\Column(name="website", type="string", length=255)
     * TODO frontend Assert\Url()
     */
    private string $website;
    /**
     * @var string
     * @ORM\Column(name="email", type="string", length=100)
     * TODO frontend Assert\Email()
     */
    private string $email;
    /**
     * @var string
     * @ORM\Column(name="return_policy", type="text")
     */
    private string $return_policy;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getIdCategory(): int
    {
        return $this->id_category;
    }

    public function setIdCategory(int $id_category): self
    {
        $this->id_category = $id_category;

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
     * @return MarketplaceCommission
     */
    public function getMarketplaceCommission(): MarketplaceCommission
    {
        return $this->marketplaceCommission;
    }

    /**
     * @param MarketplaceCommission $marketplaceCommission
     */
    public function setMarketplaceCommission(MarketplaceCommission $marketplaceCommission): self
    {
        $this->marketplaceCommission = $marketplaceCommission;

        return $this;
    }

    /**
     * @return string
     */
    public function getReturnPolicy(): string
    {
        return $this->return_policy??'';
    }

    /**
     * @param string $return_policy
     */
    public function setReturnPolicy(string $return_policy): self
    {
        $this->return_policy = $return_policy;

        return $this;
    }

    /**
     * @return string
     */
    public function getVatNumber(): string
    {
        return $this->vat_number;
    }

    /**
     * @param string $vat_number
     */
    public function setVatNumber(string $vat_number): self
    {
        $this->vat_number = $vat_number;

        return $this;
    }

    /**
     * @return string
     */
    public function getWebsite(): string
    {
        return $this->website;
    }

    /**
     * @param string $website
     */
    public function setWebsite(string $website): self
    {
        $this->website = $website;

        return $this;
    }
}
