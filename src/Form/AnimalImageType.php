<?php

namespace App\Form;

use App\Entity\AnimalImage;
use App\Entity\ProjectImage;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Validator\Constraints as Assert;

class AnimalImageType extends AbstractType
{
  public function buildForm(FormBuilderInterface $builder, array $options): void
  {
    $builder
      ->add('imageFile', VichImageType::class, [
        'label' => 'Image',
        'constraints' => [
          new Assert\File(
            maxSize: "5M",
            mimeTypes: ["image/png", "image/jpg", "image/jpeg"],
            maxSizeMessage: "L'image ne doit pas dépassée 10M.",
            mimeTypesMessage: "Format d'image non supporter, utilisez - png, jpj, jpeg",
          )
        ]
      ]);
  }

  public function configureOptions(OptionsResolver $resolver): void
  {
    $resolver->setDefaults([
      'data_class' => AnimalImage::class,
    ]);
  }
}
