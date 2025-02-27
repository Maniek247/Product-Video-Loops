<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use PrestaShop\Module\ProductVideoLoops\CQRS\Command\DeleteProductVideoCommand;
use PrestaShopBundle\Controller\Admin\FrameworkBundleAdminController;
use Symfony\Component\Routing\Annotation\Route;

class DeleteVideoController extends FrameworkBundleAdminController
{
    /**
     * @Route("/productvideoloops/delete-video/{idProduct}", name="productvideoloops_delete_video", methods={"GET"})
     * 
     * Deletes a video associated with the specified product ID, then redirects 
     * user to the product edit page
     * 
     * @param Request $request
     * 
     * @return RedirectResponse
     */
    public function deleteVideoAction(Request $request): RedirectResponse
    {
        $idProduct = (int) $request->attributes->get('idProduct');

        $this->get('prestashop.core.command_bus')->handle(new DeleteProductVideoCommand($idProduct));

        return $this->redirectToRoute('admin_products_edit', [
            'productId' => $idProduct,
        ]);
    }
}
