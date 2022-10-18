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

namespace ShoppyGo\MarketplaceBundle\Form\Widget;

use ShoppyGo\MarketplaceBundle\Entity\MarketplaceEmployeeSeller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilder;

class CategorySelectWidget
{
    protected ?MarketplaceEmployeeSeller $seller;
    protected array $categories;
    protected int $id_category = 0;

    public function addField(FormBuilder $form)
    {
        $form->add(
            'category', ChoiceType::class, array(
                'label' => 'Categories',
                'choices' => $this->categories,
                'data' => $this->id_category,
            )
        );
    }

    /**
     * @param array $categories ['name'=>id]
     * @return $this
     */
    public function setCategoryList(array $categories): self
    {
        $this->categories = $categories;

        return $this;
    }

    public function setDeafult(int $id_category): self
    {
        $this->id_category = $id_category;

        return $this;
    }
}
