<?php

namespace App\Form\Admin;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
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
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'required' => true,
                'constraints' => array(
                    new Assert\NotBlank(),
                    new Assert\Length(array(
                        'min' => 6,
                        'minMessage' => 'Le mot de passe est trop court.'
                    )),
                ),
                'first_options'  => array('label' => 'Mot de Passe: *'),
                'second_options' => array('label' => 'Confirmation du Mot de Passe: *'),
                'options' => [
                    'label_attr' => [
                        'class' => 'font-bold',
                    ],
                ],
                'invalid_message' => "Les valeurs ne correspondent pas."
            ))
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
