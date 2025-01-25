<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Form\Modifier;

use PrestaShop\Module\ProductVideoLoops\Entity\CustomCombination;
use PrestaShop\PrestaShop\Core\Domain\Product\Combination\ValueObject\CombinationId;
use PrestaShopBundle\Form\FormBuilderModifier;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Translation\TranslatorInterface;

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
        ?CombinationId $combinationId,
        FormBuilderInterface $combinationFormBuilder
    ): void {
        $idValue = $combinationId ? $combinationId->getValue() : null;
        $customCombination = new CustomCombination($idValue);
        $this->addCustomField($customCombination, $combinationFormBuilder);
    }

    /**
     * @param CustomCombination $customCombination
     * @param FormBuilderInterface $combinationFormBuilder
     *
     * @see productvideoloops::hook
     */
    private function addCustomField(CustomCombination $customCombination, FormBuilderInterface $combinationFormBuilder): void
    {
        $this->formBuilderModifier->addAfter(
            $combinationFormBuilder,
            'images',
            'demo_module_custom_field',
            TextType::class,
            [
                // you can remove the label if you dont need it by passing 'label' => false
                'label' => $this->translator->trans('Demo custom field', [], 'Modules.Demoproductform.Admin'),
                // customize label by any html attribute
                'label_attr' => [
                    'title' => 'h2',
                    'class' => 'text-info',
                ],
                'attr' => [
                    'placeholder' => $this->translator->trans('Your example text here', [], 'Modules.Demoproductform.Admin'),
                ],
                // this is just an example, but in real case scenario you could have some data provider class to wrap more complex cases
                'data' => $customCombination->filename,
                'empty_data' => '',
                'form_theme' => '@PrestaShop/Admin/TwigTemplateForm/prestashop_ui_kit_base.html.twig',
            ]
        );
    }
}
