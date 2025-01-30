<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Form\Modifier;

use PrestaShop\Module\ProductVideoLoops\Entity\ProductVideo;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShopBundle\Form\FormBuilderModifier;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\File;

final class ProductFormModifier
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var FormBuilderModifier
     */
    private $formBuilderModifier;

    /**
     * @param TranslatorInterface $translator
     * @param FormBuilderModifier $formBuilderModifier
     */
    public function __construct(
        TranslatorInterface $translator,
        FormBuilderModifier $formBuilderModifier
    ) {
        $this->translator = $translator;
        $this->formBuilderModifier = $formBuilderModifier;
    }

    /**
     * @param ProductId|null $productId
     * @param FormBuilderInterface $productFormBuilder
     */
    public function modify(
        FormBuilderInterface $productFormBuilder
    ): void {
        $this->modifyDescriptionTab($productFormBuilder);
    }

    /**
     * @param ProductVideo $productVideo
     * @param FormBuilderInterface $productFormBuilder
     *
     * @see AddProductVideoCommandHandler to check how the field is handled on form POST
     */
    private function modifyDescriptionTab(/*ProductVideo $productVideo, */FormBuilderInterface $productFormBuilder): void
    {
        $descriptionTabFormBuilder = $productFormBuilder->get('description');
        $this->formBuilderModifier->addAfter(
            $descriptionTabFormBuilder,
            'images',
            'video',
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
        );
    }
}
