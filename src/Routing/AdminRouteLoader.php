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

use Qsomazzi\Particle\Action\AddAction;
use Qsomazzi\Particle\Action\EditAction;
use Qsomazzi\Particle\Action\ListAction;
use Qsomazzi\Particle\Action\RemoveAction;
use Qsomazzi\Particle\Action\ShowAction;
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
        private readonly string $routesPrefix,
        ?string $env = null,
    ) {
        parent::__construct($env);
    }

    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        if ($this->isLoaded) {
            throw new \RuntimeException('Do not add the "admin" loader twice');
        }

        $routes  = new RouteCollection();
        foreach ($this->admins as $admin) {
            $identifierNames = explode('\\', $admin->getEntityClass());
            $identifier      = strtolower(end($identifierNames));
            $domain          = is_null($admin->getDomain()) ? '' : strtolower($admin->getDomain()).'/';

            // Create a dynamic routes for each entity
            $route = new Route(sprintf('%s/%s%s', $this->routesPrefix, $domain, $identifier), [
                '_controller' => ListAction::class,
                'admin'       => $admin::class,
                'identifier'  => $identifier,
            ], [], [], '', [], ['GET', 'POST']);
            $routes->add(sprintf('admin_%s', $identifier), $route);

            $route = new Route(sprintf('%s/%s%s/new', $this->routesPrefix, $domain, $identifier), [
                '_controller' => AddAction::class,
                'admin'       => $admin::class,
                'identifier'  => $identifier,
            ], [], [], '', [], ['GET', 'POST']);
            $routes->add(sprintf('admin_%s_new', $identifier), $route);

            $route = new Route(sprintf('%s/%s%s/{id}', $this->routesPrefix, $domain, $identifier), [
                '_controller' => ShowAction::class,
                'admin'       => $admin::class,
                'identifier'  => $identifier,
            ], [], [], '', [], ['GET']);
            $routes->add(sprintf('admin_%s_show', $identifier), $route);

            $route = new Route(sprintf('%s/%s%s/{id}/edit', $this->routesPrefix, $domain, $identifier), [
                '_controller' => EditAction::class,
                'admin'       => $admin::class,
                'identifier'  => $identifier,
            ], [], [], '', [], ['GET', 'POST']);
            $routes->add(sprintf('admin_%s_edit', $identifier), $route);

            $route = new Route(sprintf('%s/%s%s/{id}', $this->routesPrefix, $domain, $identifier), [
                '_controller' => RemoveAction::class,
                'admin'       => $admin::class,
                'identifier'  => $identifier,
            ], [], [], '', [], ['POST']);
            $routes->add(sprintf('admin_%s_delete', $identifier), $route);
        }

        $this->isLoaded = true;

        return $routes;
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return 'particle' === $type;
    }
}
