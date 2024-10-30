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
    protected string $singularLabel;
    protected string $pluralLabel;
    protected string $defaultSortColumn      = 'id';
    protected string $defaultSortDirection   = 'DESC';

    public function __construct(
        protected readonly Environment $twig,
        protected readonly EntityManagerInterface $entityManager,
        protected int $maxPerPage,
    ) {
        $this->setup();

        $this->repository = $entityManager->getRepository($this->entityClass);
    }

    abstract public function configureFields(): array;

    abstract public function setup(): void;

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
        return $this->singularLabel;
    }

    public function getPluralLabel(): string
    {
        return $this->pluralLabel;
    }

    public function getDefaultSort(): string
    {
        return $this->defaultSortColumn;
    }

    public function getDefaultSortDirection(): string
    {
        return $this->defaultSortDirection;
    }

    public function getSortableFields(): array
    {
        $sortableFields = [];
        foreach ($this->configureFields() as $field => $config) {
            if ($config['sortable']) {
                $sortableFields[] = $field;
            }
        }

        return $sortableFields;
    }

    public function getMaxPerPage(): int
    {
        return $this->maxPerPage;
    }
}
