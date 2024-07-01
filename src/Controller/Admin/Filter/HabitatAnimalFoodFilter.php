<?php

namespace App\Controller\Admin\Filter;

use App\Entity\Habitat;
use Doctrine\ORM\QueryBuilder;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Filter\FilterInterface;
use EasyCorp\Bundle\EasyAdminBundle\Dto\EntityDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FieldDto;
use EasyCorp\Bundle\EasyAdminBundle\Dto\FilterDataDto;
use EasyCorp\Bundle\EasyAdminBundle\Filter\FilterTrait;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class HabitatAnimalFoodFilter implements FilterInterface
{
    use FilterTrait;

    public static function new(string $propertyName, ?string $label = null): self
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
            $queryBuilder->join(sprintf('%s.animal', $filterDataDto->getEntityAlias()), 'a')
              // Join the habitat entity to the animal entity
              ->join('a.habitat', 'h')
              // Add the filter condition
              ->andWhere('h.id = :habitat')
              ->setParameter('habitat', $filterDataDto->getValue());
        }
    }
}
