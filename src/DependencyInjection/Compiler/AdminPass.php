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

namespace Qsomazzi\Particle\DependencyInjection\Compiler;

use Qsomazzi\Particle\Attributes\Admin;
use Qsomazzi\Particle\Metadata\AdminMetadata;
use Qsomazzi\Particle\QsomazziParticleBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AdminPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $em = $container->findDefinition('doctrine.orm.default_entity_manager');

        foreach ($container->findTaggedServiceIds('app.admin') as $id => $definition) {
            $definition = $container->findDefinition($id);

            // Get attributes
            $reflection = new \ReflectionClass($definition->getClass());
            $attributes = $reflection->getAttributes(Admin::class);
            $attribute  = end($attributes);
            $metadata   = AdminMetadata::mapFromAttributes($attribute->newInstance());

            $definition->addMethodCall('setup', [$metadata, $em]);
        }

        $bundles = $container->getParameter('kernel.bundles');

        # Override Babdev/pager-fanta config to allow the user to change the template from particle config
        if (isset($bundles['TwigBundle']) && isset($bundles['BabDevPagerfantaBundle'])) {
            $container->getDefinition('pagerfanta.twig_runtime')
                ->replaceArgument(0, 'twig');

            $container->getDefinition('pagerfanta.view.twig')
                ->replaceArgument(1, $container->getParameter(QsomazziParticleBundle::PARAMETER_TEMPLATES)['pagination']);
        }
    }
}
