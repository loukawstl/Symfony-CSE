<?php

namespace App\Form\Admin;

use App\Entity\SurveyOption;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SurveyOptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label', TextType::class, [
                'label' => 'libellé: *',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'label_attr' => [
                    'class' => 'font-bold'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => SurveyOption::class,
        ]);
    }
}
