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

namespace ShoppyGo\MarketplaceBundle\Controller;

use PrestaShop\PrestaShop\Core\Grid\Search\SearchCriteria;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use PrestaShopBundle\Security\Annotation\AdminSecurity;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceCommission;
use ShoppyGo\MarketplaceBundle\Form\Type\MarketplaceCommissionType;
use Symfony\Component\HttpFoundation\Request;

class MarketplaceCommissionController extends FrameworkBundleAdminController
{
    private $marketplaceCommissionGridFactory;

    public function deleteAction(Request $request, $id_marketplace_commission)
    {
        $entityManager = $this->getDoctrine()
            ->getManager()
        ;
        $commission = $entityManager->getRepository(MarketplaceCommission::class)
            ->find($id_marketplace_commission)
        ;

        if (!$commission) {
            throw $this->createNotFoundException('Marketplace commission not found');
        }

        try {
            $entityManager->remove($commission);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->trans('Marketplace commission successfully deleted.', 'Admin.Notifications.Success')
            );
        } catch (\Exception $e) {
            $this->addFlash(
                'error',
                $this->trans(
                    'An error occurred while deleting the marketplace commission.',
                    'Admin.Notifications.Error'
                )
            );
        }

        return $this->redirectToRoute('admin_marketplace_marketplace_commission_index');
    }

    public function editAction(Request $request, $id_marketplace_commission)
    {
        $entityManager = $this->getDoctrine()
            ->getManager()
        ;
        $commission = $entityManager->getRepository(MarketplaceCommission::class)
            ->find($id_marketplace_commission)
        ;

        if (!$commission) {
            throw $this->createNotFoundException('Marketplace commission not found');
        }

        $form = $this->createForm(MarketplaceCommissionType::class, $commission);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->trans('Marketplace commission successfully updated.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('admin_marketplace_marketplace_commission_index');
        }

        return $this->render('@ShoppyGoMarketplace/controller/commission/controller_commission_edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function indexAction(Request $request)
    {
        $grid = $this->get('shoppygo_marketplace_grid_commission_factory')
            ->getGrid(new SearchCriteria())
        ;

        return $this->render(
            '@Modules/bwmarketplace/views/templates/admin/controller/marketplace_commission_index.html.twig',
            [
                'marketplaceCommissionGrid' => $this->presentGrid($grid),
                'layoutHeaderToolbarBtn'    => [],
                'enableSidebar'             => true,
                'help_link'                 => '',
            ]
        );
    }

    public function newAction(Request $request)
    {
        $commission = new MarketplaceCommission();
        $form = $this->createForm(MarketplaceCommissionType::class, $commission);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $commission = $form->getData();
            $entityManager = $this->getDoctrine()
                ->getManager()
            ;

            $entityManager->persist($commission);
            $entityManager->flush();

            $this->addFlash(
                'success',
                $this->trans('Marketplace commission successfully created.', 'Admin.Notifications.Success')
            );

            return $this->redirectToRoute('admin_marketplace_marketplace_commission_index');
        }

        return $this->render('@ShoppyGoMarketplace/controller/commission/controller_commission_new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
