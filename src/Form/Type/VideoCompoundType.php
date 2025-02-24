<?php

namespace PrestaShop\Module\ProductVideoLoops\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;

class VideoCompoundType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $previewHtml = $options['data']['preview'] ?? '';
        $productId = $options['product_id'];
        
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

        $builder->add('productId', HiddenType::class, [
            'required' => false,
            'mapped' => false,
            'label' => false,
            'data' => $productId,
        ]);
        
        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($previewHtml, $productId) {
            $formData = $event->getData();

            if (empty($formData['productId'])) {
                $formData['productId'] = $productId;
            }
        
            if (!array_key_exists('preview', $formData)) {
                $formData['preview'] = $previewHtml;
            }
        
            $event->setData($formData);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'file_label' => null,
            'product_id' => null,
        ]);
    }

    public function getBlockPrefix()
    {
        return 'video_compound';
    }
}
