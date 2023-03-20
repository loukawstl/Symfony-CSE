<?php

namespace App\Form;

use App\Entity\Partnership;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\AbstractType;
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
                'label' => 'Images :'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Partnership::class,
        ]);
    }
}
