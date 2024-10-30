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

use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.admin')]
interface AdminInterface
{
    public function configureFields(): array;

    public function setup(): void;

    public function getEntityClass(): string;

    public function getFormClass(): string;

    public function getRepository(): EntityRepository;

    public function getDomain(): ?string;

    public function getSingularLabel(): string;

    public function getPluralLabel(): string;

    public function getDefaultSort(): string;

    public function getDefaultSortDirection(): string;

    public function getSortableFields(): array;

    public function getMaxPerPage(): int;
}
