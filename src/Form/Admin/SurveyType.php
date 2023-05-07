<?php

namespace App\Form\Admin;

use App\Entity\Survey;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Count;

class SurveyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('question', TextType::class, [
                'label' => 'Question: *',
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'label_attr' => [
                    'class' => 'font-bold'
                ],
            ])
            ->add('surveyOptions', CollectionType::class, [
                'label' => 'Options: *',
                'required' => true,
                'label_attr' => [
                    'class' => 'font-bold'
                ],
                'entry_type' => SurveyOptionType::class,
                'allow_add' => true,
                'allow_delete' => true,
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Créer le questionnaire',
                'attr' => [
                    'class' => 'bg-blue-900 text-white p-2 rounded-lg hover:bg-blue-600',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Survey::class,
        ]);
    }
}
