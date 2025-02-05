<?php

declare(strict_types=1);

/*
 * This file is part of the Particle project.
 *
 * (c) Qsomazzi
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qsomazzi\Particle\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\QueryBuilder;
use Qsomazzi\Particle\Component\Fields\Actions;
use Qsomazzi\Particle\Component\Fields\Boolean;
use Qsomazzi\Particle\Component\Fields\DateTime;
use Qsomazzi\Particle\Component\Fields\FieldInterface;
use Qsomazzi\Particle\Component\Fields\Id;
use Qsomazzi\Particle\Component\Fields\Text;
use Qsomazzi\Particle\Metadata\AdminMetadata;
use Qsomazzi\Particle\Metadata\Index;
use Qsomazzi\Particle\Metadata\OperationInterface;
use Qsomazzi\Particle\Metadata\Read;
use Qsomazzi\Particle\Metadata\Update;
use Qsomazzi\Particle\RequestPayload\IndexPayload;

abstract class AbstractAdmin
{
    private AdminMetadata $metadata;
    private ClassMetadata $doctrineMetadata;

    abstract public function configureListFields(): array;

    public function configureReadFields(): array
    {
        return $this->configureListFields();
    }

    public function configureUpdateFields(): array
    {
        return $this->configureListFields();
    }

    public function getMetadata(): AdminMetadata
    {
        return $this->metadata;
    }

    public function getQueryBuilder(EntityManagerInterface $entityManager, IndexPayload $payload): QueryBuilder
    {
        $qb = $entityManager->createQueryBuilder()
                ->select('e')
                ->from($this->metadata->getEntityClass(), 'e');

        if (!is_null($payload->query)) {
            $searchColumns = $this->getMetadata()->getDefaultSearchColumns();

            foreach ($searchColumns as $column) {
                $qb
                    ->orWhere(sprintf('LOWER(e.%1$s) LIKE :%1$s', $column))
                    ->setParameter($column, '%'.strtolower($payload->query).'%')
                ;
            }
        }

        list($sort, $sortDirection) = $this->getSort($payload);
        $qb->orderBy('e.'.$sort, $sortDirection);

        return $qb;
    }

    /**
     * This function is called automatically by the CompilerPass, shouldn't be called manually
     */
    public function setup(array $metadata, EntityManagerInterface $entityManager): void
    {
        $metadata['operations'] = AdminMetadata::hydrateOperations($metadata['operations']);

        $this->metadata         = new AdminMetadata(...$metadata);
        $this->doctrineMetadata = $entityManager->getClassMetadata($this->metadata->getEntityClass());
    }

    public function getOperationByType(string $type): ?OperationInterface
    {
        foreach ($this->metadata->getOperations() as $one) {
            if ($one::TYPE === $type) {
                return $one;
            }
        }

        return null;
    }

    public function getCustomOperations(string $location = OperationInterface::LOCATION_ACTIONS): array
    {
        $operations = [];

        foreach ($this->metadata->getOperations() as $operation) {
            if ($location === OperationInterface::LOCATION_ACTIONS && $operation->isdisplayInRowActions()) {
                $operations[] = $operation;
            } elseif ($location === OperationInterface::LOCATION_PAGE && $operation->isDisplayInPageActions()) {
                $operations[] = $operation;
            }
        }

        return $operations;
    }

    public function getFields(string $action = Index::TYPE): array
    {
        $fields           = [];
        $configuredFields = match ($action) {
            Index::TYPE  => $this->configureListFields(),
            Update::TYPE => $this->configureUpdateFields(),
            Read::TYPE   => $this->configureReadFields(),
        };

        foreach ($configuredFields as $one) {
            if ($one instanceof FieldInterface) {
                $fields[] = $one;
            } elseif ($one === 'id') {
                $fields[] = Id::new($one, 'Id');
            } elseif ($one === '_actions') {
                $fields[] = Actions::new($this->metadata->getIdentifier(), 'Actions');
            } else {
                $fields[] = $this->guessField($one);
            }
        }

        return $fields;
    }

    private function guessField(string $key, ?string $label = null): FieldInterface
    {
        $fieldMapping = $this->doctrineMetadata->getFieldMapping($key);

        return match ($fieldMapping['type']) {
            'string', 'text', 'integer' => Text::new($key, $label),
            'datetime_immutable'        => DateTime::new($key, $label),
            'boolean'                   => Boolean::new($key, $label),
            default                     => throw new \Exception(sprintf('Field type %s not handled with autoconfiguration, please configure it manually', $fieldMapping['type'])),
        };
    }

    private function getSort(IndexPayload $payload): array
    {
        $sort          = $payload->sort ?? $this->metadata->getDefaultSortColumn();
        $sortDirection = $payload->sortDirection ?? $this->metadata->getDefaultSortDirection();

        foreach ($this->getFields() as $field) {
            if ($field->isSortable() && $field->getKey() == $sort) {
                return [$sort, $sortDirection];
            }
        }

        // sort column requested is not allowed, fallback to default
        return [$this->metadata->getDefaultSortColumn(), $this->metadata->getDefaultSortDirection()];
    }
}
