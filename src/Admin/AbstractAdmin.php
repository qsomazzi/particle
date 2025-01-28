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
use Qsomazzi\Particle\Component\Fields\Actions;
use Qsomazzi\Particle\Component\Fields\Boolean;
use Qsomazzi\Particle\Component\Fields\DateTime;
use Qsomazzi\Particle\Component\Fields\FieldInterface;
use Qsomazzi\Particle\Component\Fields\Id;
use Qsomazzi\Particle\Component\Fields\Text;
use Symfony\Component\String\Inflector\EnglishInflector;
use Twig\Environment;

abstract class AbstractAdmin
{
    /**
     * @var class-string<object>
     */
    protected string $entityClass;
    protected string $formClass;
    protected EntityRepository $repository;
    protected ?string $domain = null;
    protected ?string $singularLabel = null;
    protected ?string $pluralLabel = null;
    protected ?string $defaultSearchColumn = null;
    protected string $defaultSortColumn      = 'id';
    protected string $defaultSortDirection   = 'desc';
    protected readonly EnglishInflector $inflector;

    public function __construct(
        protected readonly Environment $twig,
        protected readonly EntityManagerInterface $entityManager,
        protected int $maxPerPage,
    ) {
        $this->setup();

        $this->inflector  = new EnglishInflector();
        $this->repository = $entityManager->getRepository($this->entityClass);
    }

    abstract public function configureListFields(): array;

    abstract public function setup(): void;

    public function configureShowFields(): array
    {
        return $this->configureListFields();
    }

    public function configureEditFields(): array
    {
        return $this->configureListFields();
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getFormClass(): string
    {
        return $this->formClass;
    }

    public function getRepository(): EntityRepository
    {
        return $this->repository;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function getSingularLabel(): string
    {
        if (!is_null($this->singularLabel)) {
            return $this->singularLabel;
        }

        $name = explode('\\', $this->entityClass);
        $name = end($name);

        return $this->inflector->singularize($name)[0];
    }

    public function getPluralLabel(): string
    {
        if (!is_null($this->pluralLabel)) {
            return $this->pluralLabel;
        }

        $name = explode('\\', $this->entityClass);
        $name = end($name);

        return $this->inflector->pluralize($name)[0];
    }

    public function getDefaultSearchColumn(): ?string
    {
        return $this->defaultSearchColumn;
    }

    public function getSort(?string $sort = null, ?string $sortDirection = null): array
    {
        $sort          = $sort ?? $this->defaultSortColumn;
        $sortDirection = $sortDirection ?? $this->defaultSortDirection;

        foreach ($this->getFields() as $field) {
            if ($field->isSortable() && $field->getKey() == $sort) {
                return [$sort, $sortDirection];
            }
        }

        // sort column requested is not allowed, fallback to default
        return [$this->defaultSortColumn, $this->defaultSortDirection];
    }

    public function getMaxPerPage(): int
    {
        return $this->maxPerPage;
    }

    public function getFields(string $action = AdminInterface::ACTION_LIST): array
    {
        $fields           = [];
        $configuredFields = match ($action) {
            AdminInterface::ACTION_LIST => $this->configureListFields(),
            AdminInterface::ACTION_EDIT => $this->configureEditFields(),
            AdminInterface::ACTION_SHOW => $this->configureShowFields(),
        };

        foreach ($configuredFields as $one) {
            if ($one instanceof FieldInterface) {
                $fields[] = $one;
            } elseif ($one === 'id') {
                $fields[] = Id::new($one, 'Id');
            } elseif ($one === '_actions') {
                $fields[] = Actions::new('id', 'Actions');
            } else {
                $fields[] = $this->guessField($one);
            }
        }

        return $fields;
    }

    private function guessField(string $key, ?string $label = null): FieldInterface
    {
        $metadata     = $this->entityManager->getClassMetadata($this->entityClass);
        $fieldMapping = $metadata->getFieldMapping($key);

        return match ($fieldMapping['type']) {
            'string', 'text', 'integer' => Text::new($key, $label),
            'datetime_immutable'        => DateTime::new($key, $label),
            'boolean'                   => Boolean::new($key, $label),
            default                     => throw new \Exception(sprintf('Field type %s not handled with autoconfiguration, please configure it manually', $fieldMapping['type'])),
        };
    }
}
