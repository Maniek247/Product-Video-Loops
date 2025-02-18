<?php

declare(strict_types=1);

namespace PrestaShop\Module\ProductVideoLoops\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This form type field renders raw HTML
 */
class VideoPreviewType extends AbstractType
{
    public function getParent(): ?string
    {
        return HiddenType::class;
    }

    /**
     * Pass variables to the Twig view here
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
 
    }
    
    /**
     * Set default options here, e.g., 'label' => false
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'mapped' => false,
            'label' => false,
        ]);
    }

    /**
     * Defines the prefix used in Twig templates
     */
    public function getBlockPrefix(): string
    {
        return 'video_preview';
    }
}
