<?php

namespace App\Form\Admin;

use App\Entity\Offer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class LimitedOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'Offre: *',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'label_attr' => [
                    'class' => 'font-bold'
                ],
            ])
            ->add('text', CKEditorType::class, [
                'label' => 'Texte: *',
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'label_attr' => [
                    'class' => 'font-bold'
                ],
            ])
            ->add('dateStart', DateTimeType::class, [
                'label' => 'Début de l\'Offre: *',
                'required' => true,
                'format' => 'dd/MM/yyyy HH:mm',
                'html5' => false,
                'view_timezone' => 'Europe/Paris',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'label_attr' => [
                    'class' => 'font-bold'
                ],
            ])
            ->add('dateEnd', DateTimeType::class, [
                'label' => 'Fin de l\'Offre: *',
                'required' => true,
                'format' => 'dd/MM/yyyy HH:mm',
                'html5' => false,
                'view_timezone' => 'Europe/Paris',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'label_attr' => [
                    'class' => 'font-bold'
                ],
            ])
            ->add('tariff', CKEditorType::class, [
                'label' => 'Tarif: *',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'label_attr' => [
                    'class' => 'font-bold'
                ],
            ])
            ->add('nbMinimumPlaces', IntegerType::class, [
                'label' => 'Nombre Minimum de Places: *',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'label_attr' => [
                    'class' => 'font-bold'
                ],
            ])
            ->add('numberOrderPage', IntegerType::class, [
                'label' => 'Numéro de Page: *',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'label_attr' => [
                    'class' => 'font-bold'
                ],
            ])
            ->add('files', FileType::class, [
                'label' => 'Image(s): *',
                'required' => false,
                'mapped' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        new File([
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                                'image/svg+xml',
                                'image/webp',
                            ],
                            'mimeTypesMessage' => 'Téléchargez une image JPG, PNG, SVG ou WebP',
                            'maxSize' => '5M',
                            'maxSizeMessage' => 'L\image est trop large. La taille maximum permise est {{ limit }} {{ suffix }}',
                        ]) 
                    ])
                ],
                'attr' => [
                    'class' => 'mt-6',
                ],
                'label_attr' => [
                    'class' => 'font-bold'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $builder->getOption('on_edit') ? 'Modifier l\'Offre' : 'Créer l\'Offre',
            'attr' => [
                'class' => $builder->getOption('on_edit') ? 'bg-blue-900 text-white p-2 rounded-lg hover:bg-blue-600' : 'bg-green-800 text-white p-3 rounded-lg hover:bg-green-600',
            ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
            'on_edit' => false,
        ]);
    }
}
