<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Form\Modifier;

use PrestaShop\PrestaShop\Core\Domain\Product\Combination\ValueObject\CombinationId;
use PrestaShopBundle\Form\FormBuilderModifier;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Validator\Constraints\File;

class CombinationFormModifier
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
     * @param CombinationId|null $combinationId
     * @param FormBuilderInterface $combinationFormBuilder
     */
    public function modify( 
        FormBuilderInterface $combinationFormBuilder
    ): void {
        $this->addProductVideoField($combinationFormBuilder);
    }

    /**
     * @param FormBuilderInterface $combinationFormBuilder
     *
     * @see productvideoloops::hook
     */
    private function addProductVideoField(FormBuilderInterface $combinationFormBuilder): void
    {
        $this->formBuilderModifier->addAfter(
            $combinationFormBuilder,
            'images',
            'video',
            FileType::class,
            [
                'label' => $this->translator->trans('Video loop', [], 'Modules.Productvideoloops.Admin'),
                'mapped' => false,
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
