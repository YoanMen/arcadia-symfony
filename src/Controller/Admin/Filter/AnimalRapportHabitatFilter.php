<?php

namespace App\Controller\Admin\Filter;


use App\Entity\Habitat;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;

class AnimalRapportHabitatFilter implements FilterInterface
{
  use FilterTrait;

  public static function new(string $propertyName, ?string $label = null)
  {
    return (new self())
      ->setProperty($propertyName)
      ->setLabel($label)
      ->setFormType(EntityType::class)
      ->setFormTypeOptions([
        'class' => Habitat::class,
        'choice_label' => 'name',
      ]);
  }
  public function apply(QueryBuilder $queryBuilder, FilterDataDto $filterDataDto, ?FieldDto $fieldDto, EntityDto $entityDto): void
  {

    $value = $filterDataDto->getValue();

    if ($value) {

      $queryBuilder->andWhere(sprintf('%s.animal.habitat = :habitat', $filterDataDto->getEntityAlias()))
        ->setParameter('habitat', $filterDataDto->getValue());
    }
  }
}
