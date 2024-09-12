<?php

namespace App\Form;

use App\Entity\HabitatImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Form\Type\VichImageType;

class HabitatImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
          ->add('imageFile', VichImageType::class, [
              'label' => 'Image',
              'required' => true,
              'allow_delete' => false,
              'constraints' => [
                  new Assert\File(
                      maxSize: '5M',
                      mimeTypes: ['image/png', 'image/jpg', 'image/jpeg', 'image/webp'],
                      maxSizeMessage: "L'image ne doit pas dépassée 5M.",
                      mimeTypesMessage: "Format d'image non supporté, utilisez - png, jpg, jpeg, webp",
                  ),
              ],
          ])
          ->add('alt', TextType::class, [
              'label' => 'Description',
              'help' => 'description de l\'image pour l\'accessibilité',
              'required' => false,
          ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => HabitatImage::class,
        ]);
    }
}
