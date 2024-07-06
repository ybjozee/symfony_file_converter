<?php

namespace App\Form;

use App\Entity\Conversion;
use App\Service\ConversionService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ConversionType extends AbstractType {

    private array $availableFormats;

    public function __construct(ConversionService $conversionService) {

        $this->availableFormats = $conversionService->getAvailableFormats();
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    : void {

        $builder
            ->add(
                'convertedFileFormat',
                ChoiceType::class,
                ['choices' => $this->availableFormats, 'label' => 'Convert to']
            )
            ->add('nameToSaveAs', TextType::class, ['label' => 'Save file with name'])
            ->add('file', FileType::class, [
                'label'       => 'Input file',
                'mapped'      => false,
                'required'    => true,
                'constraints' => [
                    new File(['maxSize' => '10M']),
                ],
            ])->add('save', SubmitType::class, ['label' => 'Submit']);
    }

    public function configureOptions(OptionsResolver $resolver)
    : void {

        $resolver->setDefaults(['data_class' => Conversion::class,]);
    }
}

