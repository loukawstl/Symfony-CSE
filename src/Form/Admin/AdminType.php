<?php

namespace App\Form\Admin;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AdminType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', TextType::class, [
                'label' => 'Nom de l\'utilisateur: *',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'label_attr' => [
                    'class' => 'font-bold'
                ],
            ])
            ->add('password', PasswordType::class, [
                'label' => 'Mot de passe: *',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'label_attr' => [
                    'class' => 'font-bold'
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => $builder->getOption('on_edit') ? 'Modifier le compte' : 'Créer le compte',
                'attr' => [
                    'class' => $builder->getOption('on_edit') ? 'bg-blue-900 text-white p-2 rounded-lg hover:bg-blue-600' : 'bg-green-800 text-white p-3 rounded-lg hover:bg-green-600',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
            'on_edit' => false,
        ]);
    }
}
