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

namespace ShoppyGo\MarketplaceBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use ShoppyGo\MaketplaceBundle\Entity\Car;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Translation\TranslatorBagInterface;

class Service
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var TokenStorageInterface
     */
    protected $token;

    /**
     * @var RequestStack
     */
    protected $request;

    /**
     * @var TranslatorBagInterface
     */
    protected $translator;

    /**
     * @var string
     */
    protected $bar;

    /**
     * @var int
     */
    protected $integerFoo;

    /**
     * @var int
     */
    protected $integerBar;

    public function __construct(
        EntityManagerInterface $em,
        TokenStorageInterface $token,
        RequestStack $requestStack,
        TranslatorBagInterface $translator,
        $bar,
        $integerFoo,
        $integerBar
    ) {
        $this->em = $em;
        $this->token = $token;
        $this->request = $requestStack->getCurrentRequest();
        $this->translator = $translator;
        $this->bar = $bar;
        $this->integerFoo = (int) $integerFoo;
        $this->integerBar = (int) $integerBar;
    }

    public function foo($a, $b)
    {
        return 'This is an uncertain ' . $this->translator->trans($this->bar[0]) . ' output ' . ($a + $b) * $this->integerFoo / $this->integerBar;
    }

    public function getCars()
    {
        $user = $this->token->getToken()->getUser();
        if (!method_exists($user, 'getUsername')) {
            $user = null;
        }
        $carRepository = $this->em->getRepository(Car::class);

        return $carRepository->findBy(['user' => $user]);
    }

    public function createCar($brand, $model)
    {
        $car = new Car();
        $user = $this->token->getToken()->getUser();

        $car->setBrand($brand);
        $car->setModel($model);
        if (method_exists($user, 'getUsername')) {
            $car->setUser($user);
        }

        $this->em->persist($car);
        $this->em->flush();

        return $car;
    }
}
