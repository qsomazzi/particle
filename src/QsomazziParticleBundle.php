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

namespace Qsomazzi\Particle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class QsomazziParticleBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $node = $definition->rootNode();

        // @phpstan-ignore-next-line
        $node
            ->children()
                ->scalarNode('routesPrefix')->defaultValue('/admin')->end()
                ->integerNode('maxPerPage')->defaultValue(10)->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        $container->services()
            ->get('Qsomazzi\\Particle\\Routing\\AdminRouteLoader')
            ->arg('$routesPrefix', $config['routesPrefix'])
        ;

        $container->parameters()->set('qsomazzi_particle.max_per_page', $config['maxPerPage']);
    }
}
