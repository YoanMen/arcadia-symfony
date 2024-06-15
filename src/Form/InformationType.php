<?php

namespace App\Form;

use App\Entity\Animal;
use App\Entity\AnimalInformation;
use App\Entity\Region;
use App\Entity\Species;
use App\Entity\UICN;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class InformationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('sizeAndHeight')
            ->add('lifespan')
            ->add('uicn', EntityType::class, [
                'class' => UICN::class,
'choice_label' => 'id',
            ])
            ->add('region', EntityType::class, [
                'class' => Region::class,
'choice_label' => 'id',
            ])
            ->add('animal', EntityType::class, [
                'class' => Animal::class,
'choice_label' => 'id',
            ])
            ->add('species', EntityType::class, [
                'class' => Species::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => AnimalInformation::class,
        ]);
    }
}
