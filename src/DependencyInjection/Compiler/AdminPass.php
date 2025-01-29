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
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AdminPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        foreach ($container->findTaggedServiceIds('app.admin') as $id => $definition) {
            $definition = $container->findDefinition($id);

            // Get attributes
            $reflection = new \ReflectionClass($definition->getClass());
            $attributes = $reflection->getAttributes(Admin::class);
            $attribute  = end($attributes);
            $metadata   = AdminMetadata::mapFromAttributes($attribute->newInstance());

            $definition->addMethodCall('setup', [$metadata]);
        }
    }
}
