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
        foreach ($this->admins as $admin) {
            foreach ($admin->getMetadata()->getOperations() as $config) {
                $route = new Route($config['path'], [
                    '_controller' => $config['controller'],
                    'admin'       => $admin::class,
                ], [], [], '', [], $config['methods']);
                $routes->add($config['name'], $route);
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
