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
use Doctrine\ORM\QueryBuilder;
use Qsomazzi\Particle\Metadata\AdminMetadata;
use Qsomazzi\Particle\Metadata\Index;
use Qsomazzi\Particle\RequestPayload\IndexPayload;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('app.admin')]
interface AdminInterface
{
    public function configureListFields(): array;

    public function configureReadFields(): array;

    public function configureUpdateFields(): array;

    public function getMetadata(): AdminMetadata;

    public function getQueryBuilder(EntityManagerInterface $entityManager, IndexPayload $payload): QueryBuilder;

    public function setup(array $metadata, EntityManagerInterface $entityManager): void;

    public function getFields(string $action = Index::TYPE): array;
}
