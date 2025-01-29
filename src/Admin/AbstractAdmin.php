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
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Mapping\ClassMetadata;
use Qsomazzi\Particle\Component\Fields\Actions;
use Qsomazzi\Particle\Component\Fields\Boolean;
use Qsomazzi\Particle\Component\Fields\DateTime;
use Qsomazzi\Particle\Component\Fields\FieldInterface;
use Qsomazzi\Particle\Component\Fields\Id;
use Qsomazzi\Particle\Component\Fields\Text;
use Qsomazzi\Particle\Metadata\AdminMetadata;
use Qsomazzi\Particle\Metadata\Index;
use Qsomazzi\Particle\Metadata\Read;
use Qsomazzi\Particle\Metadata\Update;

abstract class AbstractAdmin
{
    private AdminMetadata $metadata;
    private ClassMetadata $doctrineMetadata;
    private EntityRepository $repository;

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
    ) {
    }

    abstract public function configureListFields(): array;

    public function configureReadFields(): array
    {
        return $this->configureListFields();
    }

    public function configureUpdateFields(): array
    {
        return $this->configureListFields();
    }

    public function getRepository(): EntityRepository
    {
        return $this->repository;
    }

    public function getMetadata(): AdminMetadata
    {
        return $this->metadata;
    }

    /**
     * This function is called automatically by the CompilerPass, shouldn't be called manually
     */
    public function setup(array $metadata): void
    {
        $this->metadata         = new AdminMetadata(...$metadata);
        $this->doctrineMetadata = $this->entityManager->getClassMetadata($this->metadata->getEntityClass());
        $this->repository       = $this->entityManager->getRepository($this->metadata->getEntityClass());
    }

    public function getOperationName(string $operation): ?string
    {
        $operations = $this->metadata->getOperations();
        foreach ($operations as $one) {
            if ($one['type'] === $operation) {
                return $one['name'];
            }
        }

        return null;
    }






















    public function getSort(?string $sort = null, ?string $sortDirection = null): array
    {
        $sort          = $sort ?? $this->metadata->getDefaultSortColumn();
        $sortDirection = $sortDirection ?? $this->metadata->getDefaultSortDirection();

        foreach ($this->getFields() as $field) {
            if ($field->isSortable() && $field->getKey() == $sort) {
                return [$sort, $sortDirection];
            }
        }

        // sort column requested is not allowed, fallback to default
        return [$this->metadata->getDefaultSortColumn(), $this->metadata->getDefaultSortDirection()];
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
}
