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

use Qsomazzi\Particle\DependencyInjection\Compiler\AdminPass;
use Qsomazzi\Particle\DependencyInjection\Compiler\TwigPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class QsomazziParticleBundle extends AbstractBundle
{
    public const string PARAMETER_PROJECT_NAME = 'qsomazzi_particle.project_name';
    public const string PARAMETER_PROJECT_ICON = 'qsomazzi_particle.project_icon';
    public const string PARAMETER_FAVICON = 'qsomazzi_particle.favicon';
    public const string PARAMETER_HOMEPAGE = 'qsomazzi_particle.homepage';
    public const string PARAMETER_LOGOUT = 'qsomazzi_particle.logout';
    public const string PARAMETER_HEADER_LINKS = 'qsomazzi_particle.header_links';
    public const string PARAMETER_FOOTER_LINKS = 'qsomazzi_particle.footer_links';
    public const string PARAMETER_SEARCH = 'qsomazzi_particle.search';

    public function configure(DefinitionConfigurator $definition): void
    {
        $node = $definition->rootNode();

        // @phpstan-ignore-next-line
        $node
            ->children()
                ->scalarNode('projectName')->defaultValue('Particle')->end()
                ->scalarNode('projectIcon')->defaultValue('favicon.jpeg')->end()
                ->scalarNode('favicon')->defaultValue('favicon.ico')->end()
                ->scalarNode('homepage')->defaultValue('homepage')->end()
                ->scalarNode('logout')->defaultValue('logout')->end()
                ->arrayNode('headerLinks')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('url')->end()
                            ->scalarNode('label')->end()
                            ->scalarNode('icon')->defaultValue(null)->end()
                            ->scalarNode('target')->defaultValue('_blank')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('footerLinks')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('url')->end()
                            ->scalarNode('label')->end()
                            ->scalarNode('target')->defaultValue('_blank')->end()
                        ->end()
                    ->end()
                ->end()
                ->booleanNode('search')->defaultTrue()->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        $container->parameters()->set(self::PARAMETER_PROJECT_NAME, $config['projectName']);
        $container->parameters()->set(self::PARAMETER_PROJECT_ICON, $config['projectIcon']);
        $container->parameters()->set(self::PARAMETER_FAVICON, $config['favicon']);
        $container->parameters()->set(self::PARAMETER_HOMEPAGE, $config['homepage']);
        $container->parameters()->set(self::PARAMETER_LOGOUT, $config['logout']);
        $container->parameters()->set(self::PARAMETER_HEADER_LINKS, $config['headerLinks']);
        $container->parameters()->set(self::PARAMETER_FOOTER_LINKS, $config['footerLinks']);
        $container->parameters()->set(self::PARAMETER_SEARCH, $config['search']);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AdminPass());
        $container->addCompilerPass(new TwigPass());
    }
}
