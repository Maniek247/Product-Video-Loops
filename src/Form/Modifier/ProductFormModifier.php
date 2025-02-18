<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Form\Modifier;

use PrestaShop\Module\ProductVideoLoops\Entity\ProductVideo;
use PrestaShop\Module\ProductVideoLoops\Factory\ProductVideoFactory;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShopBundle\Form\FormBuilderModifier;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use PrestaShop\Module\ProductVideoLoops\Form\Type\VideoPreviewType;
use PrestaShop\Module\ProductVideoLoops\Form\Type\VideoCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\File;
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
                ],
                'data' => [
                    'preview' => $videoPreviewHtml, // przekazanie html do 'preview' czyli podktypu HiddenType
                ],
        
                'form_theme' => 
                    '@Modules/productvideoloops/views/templates/admin/forms/video_compound.html.twig',
            ]
        );
        
       /* $this->formBuilderModifier->addAfter(
            $descriptionTabFormBuilder,
            'video_block',
            'video_preview',
            VideoPreviewType::class,
            [
                'label' => $this->translator->trans('Current video', [], 'Modules.Productvideoloops.Admin'),
                'mapped' => false,
                'required' => false,
                'data' => $videoPreviewHtml,
                'empty_data' => $this->translator->trans('There is no video added yet', [], 'Modules.Productvideoloops.Admin'),
                // Ważne: powyższa metoda wstrzykuje <video> w postaci surowego HTML.
                // Za chwilę wytłumaczę, jak to może wyglądać.
                'attr' => [
                    'readonly' => 'readonly',  // żeby nikt nie grzebał w tym polu
                    'style' => 'height:auto;', // żeby tekstarea była wysokosci dopasowanej
                ],
                'form_theme' => [
                    '@Modules/productvideoloops/views/templates/admin/forms/video_preview.html.twig',
                ],
            ]
        );
        
        $this->formBuilderModifier->addAfter(
            $descriptionTabFormBuilder,
            'video_preview',
            'video_upload',
            FileType::class,
            [
                'label' => $this->translator->trans('Video loop', [], 'Modules.Productvideoloops.Admin'),
                'mapped' => true,
                'required' => false,
                'empty_data' => '',
                'form_theme' => '@PrestaShop/Admin/TwigTemplateForm/prestashop_ui_kit_base.html.twig',
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => [
                            'video/mp4'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid .mp4 video',
                    ]),
                ]
            ]
        ); */
    }
}
