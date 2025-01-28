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
    public const ACTION_EDIT = 'edit';
    public const ACTION_LIST = 'list';
    public const ACTION_SHOW = 'show';

    public function configureListFields(): array;
    public function configureShowFields(): array;
    public function configureEditFields(): array;

    public function setup(): void;

    public function getEntityClass(): string;

    public function getFormClass(): string;

    public function getRepository(): EntityRepository;

    public function getDomain(): ?string;

    public function getSingularLabel(): string;

    public function getPluralLabel(): string;

    public function getDefaultSearchColumn(): ?string;

    public function getSort(?string $sort = null, ?string $sortDirection = null): array;

    public function getMaxPerPage(): int;
}
