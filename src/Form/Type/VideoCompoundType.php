<?php

declare(strict_types=1);

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

/**
 * Compound form type that includes:
 * - A file upload field
 * - A hidden preview field
 * - A hidden product ID
 *
 * A PRE_SUBMIT event listener fills in missing preview/product ID data 
 * if a user attempts to upload an invalid file (to not lose the old preview)
 */
class VideoCompoundType extends AbstractType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     *
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
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

    /**
     * @param OptionsResolver $resolver
     *
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'product_id' => null,
            'custom_label' => null,
        ]);
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     *
     * @return void
     */
    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        $view->vars['custom_label'] = $options['custom_label'];
    }

    /**
     * @return string
     */
    public function getBlockPrefix(): string
    {
        return 'video_compound';
    }
}
