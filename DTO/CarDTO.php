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

namespace ShoppyGo\MarketplaceBundle\DTO;

use ShoppyGo\MaketplaceBundle\Entity\Car;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class CarDTO implements \JsonSerializable
{
    const ID = 'id';
    const BRAND = 'brand';
    const MODEL = 'model';

    protected $id;
    protected $brand;
    protected $model;

    public function __construct($id, $brand, $model)
    {
        $this->id = $id;
        $this->brand = $brand;
        $this->model = $model;
    }

    public static function fromRequest(Request $request)
    {
        $builder = new CarDTOBuilder();

        return $builder
            ->withId($request->request->get(self::ID))
            ->withBrand($request->request->get(self::BRAND))
            ->withModel($request->request->get(self::MODEL))
            ->build();

    }

    public static function toResponse(CarDTO $carDTO)
    {
        $response = new Response();
        $response->setContent($carDTO->jsonSerialize());

        return $response;
    }

    public static function toDTO(Car $car)
    {
        $builder = new CarDTOBuilder();

        return $builder
            ->withId($car->getId())
            ->withBrand($car->getBrand())
            ->withModel($car->getModel())
            ->build();
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getBrand()
    {
        return $this->brand;
    }

    public function setBrand($brand)
    {
        $this->brand = $brand;
    }

    public function getModel()
    {
        return $this->model;
    }

    public function setModel($model)
    {
        $this->model = $model;
    }

    public function jsonSerialize()
    {
        return array(
            'id' => $this->id,
            'brand' => $this->brand,
            'model' => $this->model,
        );
    }
}
