<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Form\Modifier;

use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShopBundle\Form\FormBuilderModifier;
use PrestaShop\Module\ProductVideoLoops\Form\Type\VideoCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use PrestaShop\Module\ProductVideoLoops\Service\ProductVideoPreviewService;

final class ProductFormModifier
{
    private $videoFactory;
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FormBuilderModifier
     */
    private $formBuilderModifier;

       /**
     * @var ProductVideoPreviewService
     */
    private $videoPreviewService;

    /**
     * @param TranslatorInterface $translator
     * @param FormBuilderModifier $formBuilderModifier
     * @param ProductVideoPreviewServide $videoPreviewService
     */
    public function __construct(
        TranslatorInterface $translator,
        FormBuilderModifier $formBuilderModifier,
        ProductVideoPreviewService $videoPreviewService

    ) {
        $this->translator = $translator;
        $this->formBuilderModifier = $formBuilderModifier;
        $this->videoPreviewService = $videoPreviewService;
    }

    /**
     * @param ProductId|null $productId
     * @param FormBuilderInterface $productFormBuilder
     */
    public function modify(
        ?int $idProduct,
        FormBuilderInterface $productFormBuilder
    ): void {
        $this->modifyDescriptionTab($idProduct, $productFormBuilder);
    }

    /**
     * @param FormBuilderInterface $productFormBuilder
     *
     * @see AddProductVideoCommandHandler to check how the field is handled on form POST
     */
    private function modifyDescriptionTab(?int $idProduct, FormBuilderInterface $productFormBuilder): void
    {
        $descriptionTabFormBuilder = $productFormBuilder->get('description');

        $videoPreviewHtml = '';
        if ($idProduct) {
            $videoPreviewHtml = $this->videoPreviewService->getPreviewHtml($idProduct);
            if (!$videoPreviewHtml) {
                $videoPreviewHtml = '';
            }
        }
        
        $this->formBuilderModifier->addAfter(
            $descriptionTabFormBuilder,
            'images',
            'video_block',
            VideoCompoundType::class,
            [
                'label' => $this->translator->trans('Video loop block', [], 'Modules.Productvideoloops.Admin'),
                'required' => false,
                'mapped' => [
                    'preview' => false,
                    'file' => true,
                    'productId' => false,
                ],
                'data' => [
                    'preview' => $videoPreviewHtml,
                ],
                'product_id' => $idProduct,
                'form_theme' => 
                    '@Modules/productvideoloops/views/templates/admin/forms/video_compound.html.twig',
            ]
        );
    }
}
