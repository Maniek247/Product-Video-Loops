<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Form\Modifier;

use PrestaShop\Module\ProductVideoLoops\CQRS\CommandHandler\UpdateCustomProductCommandHandler;
use PrestaShop\Module\ProductVideoLoops\Entity\CustomProduct;
use PrestaShop\PrestaShop\Core\Domain\Product\ValueObject\ProductId;
use PrestaShopBundle\Form\FormBuilderModifier;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

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
        ?ProductId $productId,
        FormBuilderInterface $productFormBuilder
    ): void {
        $idValue = $productId ? $productId->getValue() : null;
        $customProduct = new CustomProduct($idValue);
        $this->modifyDescriptionTab($customProduct, $productFormBuilder);
    }

    /**
     * @param CustomProduct $customProduct
     * @param FormBuilderInterface $productFormBuilder
     *
     * @see UpdateCustomProductCommandHandler to check how the field is handled on form POST
     */
    private function modifyDescriptionTab(CustomProduct $customProduct, FormBuilderInterface $productFormBuilder): void
    {
        $descriptionTabFormBuilder = $productFormBuilder->get('description');
        $this->formBuilderModifier->addAfter(
            $descriptionTabFormBuilder,
            'images',
            'product_video_field',
            FileType::class,
            [
                // you can remove the label if you dont need it by passing 'label' => false
                'label' => $this->translator->trans('Product video', [], 'Modules.Productvideoloops.Admin'),
                // customize label by any html attribute
                'label_attr' => [
                    'title' => 'h3',
                  //  'class' => 'text-info',
                ],
             /*     'attr' => [
                  'placeholder' => $this->translator->trans('Your example text here', [], 'Modules.Demoproductform.Admin'),
                ], */
                // this is just an example, but in real case scenario you could have some data provider class to wrap more complex cases
                'data' => $customProduct->filename,
                'empty_data' => '',
                'form_theme' => '@PrestaShop/Admin/TwigTemplateForm/prestashop_ui_kit_base.html.twig',
            ]
        );
    }
}
