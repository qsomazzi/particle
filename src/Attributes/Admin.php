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

namespace Qsomazzi\Particle\Attributes;

#[\Attribute(\Attribute::TARGET_CLASS)]
readonly final class Admin
{
    public function __construct(
        private string  $formClass,
        private string  $entityClass,
        private string  $identifier = 'id',
        private string  $prefix = '/admin',
        private int     $maxPerPage = 10,
        private string  $defaultSortColumn = 'id',
        private string  $defaultSortDirection = 'desc',
        private array   $defaultSearchColumns = [],
        private ?array  $operations = null,
        private ?string $domain = null,
        private ?string $singularLabel = null,
        private ?string $pluralLabel = null,
        private ?string $shortName = null,
    ) {
    }

    public function getFormClass(): string
    {
        return $this->formClass;
    }

    public function getEntityClass(): string
    {
        return $this->entityClass;
    }

    public function getIdentifier(): string
    {
        return $this->identifier;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getMaxPerPage(): int
    {
        return $this->maxPerPage;
    }

    public function getDefaultSortColumn(): string
    {
        return $this->defaultSortColumn;
    }

    public function getDefaultSortDirection(): string
    {
        return $this->defaultSortDirection;
    }

    public function getDefaultSearchColumns(): array
    {
        return $this->defaultSearchColumns;
    }

    public function getOperations(): ?array
    {
        return $this->operations;
    }

    public function getDomain(): ?string
    {
        return $this->domain;
    }

    public function getSingularLabel(): ?string
    {
        return $this->singularLabel;
    }

    public function getPluralLabel(): ?string
    {
        return $this->pluralLabel;
    }

    public function getShortName(): ?string
    {
        return $this->shortName;
    }
}