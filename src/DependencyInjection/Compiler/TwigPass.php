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

use Qsomazzi\Particle\QsomazziParticleBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class TwigPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('twig')) {
            return;
        }

        $def = $container->getDefinition('twig');
        $def->addMethodCall('addGlobal', ['projectName', $container->getParameter(QsomazziParticleBundle::PARAMETER_PROJECT_NAME)]);
        $def->addMethodCall('addGlobal', ['projectIcon', $container->getParameter(QsomazziParticleBundle::PARAMETER_PROJECT_ICON)]);
        $def->addMethodCall('addGlobal', ['favicon', $container->getParameter(QsomazziParticleBundle::PARAMETER_FAVICON)]);
        $def->addMethodCall('addGlobal', ['prefix', $container->getParameter(QsomazziParticleBundle::PARAMETER_PREFIX)]);
        $def->addMethodCall('addGlobal', ['logout', $container->getParameter(QsomazziParticleBundle::PARAMETER_LOGOUT)]);
        $def->addMethodCall('addGlobal', ['search', $container->getParameter(QsomazziParticleBundle::PARAMETER_SEARCH)]);

        $def->addMethodCall('addGlobal', ['homepage', $container->getParameter(QsomazziParticleBundle::PARAMETER_HOMEPAGE)]);

        $def->addMethodCall('addGlobal', ['headerLinks', $container->getParameter(QsomazziParticleBundle::PARAMETER_HEADER_LINKS)]);
        $def->addMethodCall('addGlobal', ['dashboardLinks', $container->getParameter(QsomazziParticleBundle::PARAMETER_DASHBOARD_LINKS)]);
        $def->addMethodCall('addGlobal', ['footerLinks', $container->getParameter(QsomazziParticleBundle::PARAMETER_FOOTER_LINKS)]);
    }
}
