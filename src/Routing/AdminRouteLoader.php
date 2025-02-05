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

namespace Qsomazzi\Particle\Routing;

use Qsomazzi\Particle\Metadata\OperationInterface;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

#[AutoconfigureTag('routing.loader')]
final class AdminRouteLoader extends Loader
{
    private bool $isLoaded = false;

    public function __construct(
        #[TaggedIterator('app.admin')] private readonly iterable $admins,
        private readonly string $qsomazziParticlePrefix,
        private readonly array $qsomazziParticleHomepage,
        ?string $env = null,
    ) {
        parent::__construct($env);
    }

    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        if ($this->isLoaded) {
            throw new \RuntimeException('Do not add the "particle" loader twice');
        }

        $routes = new RouteCollection();

        // Add the homepage route
        $route = new Route($this->qsomazziParticlePrefix.'/', [
            '_controller' => $this->qsomazziParticleHomepage['controller'],
        ], [], [], '', [], ['GET']);
        $routes->add($this->qsomazziParticleHomepage['route'], $route);

        // Add the routes for each admin
        foreach ($this->admins as $admin) {
            foreach ($admin->getMetadata()->getOperations() as $operation) {
                if ($operation instanceof OperationInterface) {
                    $route = new Route($this->qsomazziParticlePrefix.$operation->getPath(), [
                        '_controller' => $operation->getController(),
                        'admin'       => $admin::class,
                    ], [], [], '', [], $operation->getMethods());
                    $routes->add($operation->getName(), $route);
                }
            }
        }

        $this->isLoaded = true;

        return $routes;
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return 'particle' === $type;
    }
}
