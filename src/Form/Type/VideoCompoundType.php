<?php

namespace PrestaShop\Module\ProductVideoLoops\Form\Type;

use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormView;
use Symfony\Component\Form\FormInterface;

class VideoCompoundType extends AbstractType
{
    private $translator;

    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $previewHtml = $options['data']['preview'] ?? '';
        $productId = $options['product_id'];
        
        $builder->add('file', FileType::class, [
            'required' => false,
            'mapped' => true,
            'label' => $this->translator->trans('Upload file', [], 'Modules.Productvideoloops.Videocompoundtype'),
            'help' => $this->translator->trans('Uploaded video will always be set as a cover and will be the first thumbnail', [], 'Modules.Productvideoloops.Videocompoundtype'),
            'constraints' => [
                new File([
                    'maxSize' => '5M',
                    'maxSizeMessage' => $this->translator->trans('The file is too large ({{ size }} {{ suffix }}). Allowed maximum size is {{ limit }} {{ suffix }}.', [], 'Modules.Productvideoloops.Videocompoundtype'),
                    'mimeTypes' => [
                        'video/mp4'
                    ],
                    'mimeTypesMessage' => $this->translator->trans('Uploaded type of the file is invalid ({{ type }}). Allowed video type is {{ types }}.', [], 'Modules.Productvideoloops.Videocompoundtype'),
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
            'product_id' => null,
            'custom_label' => null,
        ]);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['custom_label'] = $options['custom_label'];
    }

    public function getBlockPrefix()
    {
        return 'video_compound';
    }
}
