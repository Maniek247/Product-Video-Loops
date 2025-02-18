<?php

namespace PrestaShop\Module\ProductVideoLoops\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VideoCompoundType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $previewHtml = $options['data']['preview'] ?? '';

        $builder->add('file', FileType::class, [
            'required' => false,
            'mapped' => true,
            'label' => $options['file_label'] ?? 'Video file',
            'constraints' => [
                new File([
                    'maxSize' => '5M',
                    'mimeTypes' => [
                        'video/mp4'
                    ],
                    'mimeTypesMessage' => 'Please upload a valid .mp4 video',
                ]),
            ]
        ]);

        $builder->add('preview', HiddenType::class, [
            'required' => false,
            'mapped' => false,
            'label' => false,
            'data' => $previewHtml,
        ]);

        // TODO: delete button - HiddenType?
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'file_label' => null,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'video_compound';
    }
}
