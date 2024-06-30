<?php

namespace App\Form;

use App\DTO\ContactDTO;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ContactType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'name',
                TextType::class,
                [
                    'label' => 'nom',
                    'required' => true,
                    'empty_data' => '',
                    'row_attr' => [
                        'class' => 'flex flex-col'
                    ],
                    'label_attr' => [
                        'class' => 'font-montserrat font-medium'
                    ],
                    'attr' => [
                        'minlength' => '3',
                        'maxlength' => '200',
                        'class' => 'mt-2 w-full h-10 p-2 border-color outline-0 rounded bg-border'
                    ]
                ]
            )
            ->add('email', EmailType::class, [
                'label' => 'adresse email',
                'empty_data' => '',
                'required' => true,
                'row_attr' => array(
                    'class' => 'flex flex-col'
                ),
                'label_attr' => [
                    'class' => 'font-montserrat font-medium'
                ],
                'attr' =>
                [
                    'minlength' => '3',
                    'maxlength' => '320',
                    'class' => 'mt-2 w-full h-10 p-2 border-color outline-0 rounded bg-border'
                ],
            ])
            ->add('contactType', ChoiceType::class, [
                'label' => 'type de contact',

                'choices'  => [
                    'information' =>  0,
                    'visite pour une école' => 1,
                    'visite en groupe' =>  2,
                    'autre' => 3,
                ],
                'label_attr' => [
                    'class' => 'font-montserrat font-medium'
                ],
                'choice_attr' => function () {
                    return ['class' => 'text-black '];
                },
                'attr' =>
                [
                    'class' => 'mt-2 w-full h-10 p-2 border-color outline-0 rounded bg-border'
                ],
            ])
            ->add('message', TextareaType::class, [
                'label' => 'message',
                'required' => true,
                'empty_data' => '',
                'label_attr' => [
                    'class' => 'font-montserrat font-medium'
                ],
                'attr' =>
                [
                    'minlength' => '10',
                    'class' => 'mt-2 w-full h-40 p-2 border-color outline-0 rounded bg-border resize-none '
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => false,
                'required' => true,

            ])
            ->add('save', SubmitType::class, [
                'label' => 'envoyer',
                'attr' => [
                    'class' => 'w-full rounded text-sm p-3 px-3 btn-primary  '
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ContactDTO::class,
        ]);
    }
}
