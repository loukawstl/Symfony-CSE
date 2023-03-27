<?php

namespace App\Form;

use App\Entity\Partnership;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PartnershipType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('name', TextType::class, [
            'label' => 'Nom :'
        ])
            ->add('text', TextType::class, [
                'label' => 'Texte :'
            ])
            ->add('linkToWebsite', TextType::class, [
                'label' => 'Lien :'
            ])
            ->add('file', FileType::class, [
                'label' => 'Image :',
                'required' => false,
                'mapped' => false,
                'multiple' => false,
                'constraints' => [
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
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $builder->getOption('on_edit') ? 'Modifier' : 'Créer',
            'attr' => [
                'class' => $builder->getOption('on_edit') ? 'bg-blue-900 text-white p-2 rounded-lg hover:bg-blue-600' : 'bg-green-800 text-white p-3 rounded-lg hover:bg-green-600',
            ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partnership::class,
            'on_edit' => false,
        ]);
    }
}
