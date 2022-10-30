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

use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use ShoppyGo\MarketplaceBundle\Classes\MarketplaceConfiguration;
use ShoppyGo\MarketplaceBundle\Domain\Seller\Command\ToggleSellerCommand;
use ShoppyGo\MarketplaceBundle\Entity\MarketplaceCategory;
use ShoppyGo\MarketplaceBundle\Form\Type\ConfigurationType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class MarketplaceConfigurationController extends FrameworkBundleAdminController
{
    public function configuration(Request $request)
    {
        $core = $this->get('shoppygo.core');
        if ($core->isEmployeeSeller()) {
            return $this->redirectToRoute('admin_suppliers_index');
        }

        $conf = $this->get('prestashop.adapter.legacy.configuration');

        $data = [];
        foreach (MarketplaceConfiguration::CONFIGURATION_KEYS as $key) {
            $data[$key] = $conf->get($key);
        }

        $form = $this->createForm(ConfigurationType::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            foreach ($data as $conf_name => $conf_value) {
                $conf->set($conf_name, $conf_value);
            }
            $this->addFlash('success', 'Configurazione salvata');
        }

        return $this->render(
            '@ShoppyGoMarketplace/controller/marketplace_configuration.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }

    public function installDemo(Request $request)
    {
//        $fixture = new DemoFixtures($this->get('bwmarketplace.module'), $this->getDoctrine());
//        $fixture->resetMarketplaceDb();
//        $fixture->resetData();
//        $fixture->installSellerData();
//
//        $this->addFlash('success', $this->trans('Demo data installd', 'Amind.Marketplace.Configuration'));
//
//        return $this->redirectToRoute('admin_marketplace_configuration');
    }

    public function reinstallHooks(Request $request)
    {
//        if ($this->get('bwmarketplace.module')->installHooks()) {
//            $this->addFlash('success', $this->trans('Hooks reinstalled.', 'Amind.Marketplace.Configuration'));
//
//        } else {
//            $this->addFlash('error', $this->trans('Error in hooks reinstallation', 'Amind.Marketplace.Configuration'));
//
//        }

//        return $this->redirectToRoute('admin_marketplace_configuration');
    }

    public function toggleSellerCategory($id): JsonResponse
    {
        $entityName = MarketplaceCategory::class;
        $marketplace_category = $this->getDoctrine()
            ->getRepository($entityName)
            ->find($id)
        ;
        $response = [];
        if (!$marketplace_category) {
            $response = [
                'status' => false,
                'message' => $this->trans('Category marketplace doesn\'t exist.', 'Admin.Notifications.Error'),
            ];
        } else {
            $this->getCommandBus()
                ->handle(
                    new ToggleSellerCommand(
                        (int) $id, 'id_category', $entityName, !$marketplace_category->isSeller()
                    )
                )
            ;
            $response = [
                'status' => true,
                'message' => $this->trans('The status has been successfully updated.', 'Admin.Notifications.Success'),
            ];
        }

        $this->addFlash('success', $this->trans('Toggle success!', ''));

        return $this->json($response);
    }
}
