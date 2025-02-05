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

use Qsomazzi\Particle\Action\HomepageAction;
use Qsomazzi\Particle\DependencyInjection\Compiler\AdminPass;
use Qsomazzi\Particle\DependencyInjection\Compiler\TwigPass;
use Qsomazzi\Particle\Metadata\Create;
use Qsomazzi\Particle\Metadata\Index;
use Qsomazzi\Particle\Metadata\Read;
use Qsomazzi\Particle\Metadata\Update;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class QsomazziParticleBundle extends AbstractBundle
{
    public const string PARAMETER_PROJECT_NAME = 'qsomazzi_particle.project_name';
    public const string PARAMETER_PROJECT_ICON = 'qsomazzi_particle.project_icon';
    public const string PARAMETER_FAVICON = 'qsomazzi_particle.favicon';
    public const string PARAMETER_PREFIX = 'qsomazzi_particle.prefix';
    public const string PARAMETER_HOMEPAGE = 'qsomazzi_particle.homepage';
    public const string PARAMETER_LOGOUT = 'qsomazzi_particle.logout';
    public const string PARAMETER_SEARCH = 'qsomazzi_particle.search';

    public const string PARAMETER_HEADER_LINKS = 'qsomazzi_particle.header_links';
    public const string PARAMETER_DASHBOARD_LINKS = 'qsomazzi_particle.dashboard_links';
    public const string PARAMETER_FOOTER_LINKS = 'qsomazzi_particle.footer_links';

    public const string PARAMETER_TEMPLATES = 'qsomazzi_particle.templates';

    public function configure(DefinitionConfigurator $definition): void
    {
        $node = $definition->rootNode();

        // @phpstan-ignore-next-line
        $node
            ->children()
                ->scalarNode('projectName')->defaultValue('Particle')->end()
                ->scalarNode('projectIcon')->defaultValue('bundles/qsomazziparticle/logo_large.png')->end()
                ->scalarNode('favicon')->defaultValue('bundles/qsomazziparticle/favicon.jpg')->end()
                ->scalarNode('prefix')->defaultValue('/admin')->end()
                ->scalarNode('logout')->defaultValue('logout')->end()
                ->booleanNode('search')->defaultTrue()->end()
                ->arrayNode('homepage')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('route')->defaultValue('homepage')->isRequired()->end()
                        ->scalarNode('controller')->defaultValue(HomepageAction::class)->isRequired()->end()
                    ->end()
                ->end()
                ->arrayNode('templates')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('homepage')->defaultValue('@Particle/Action/homepage.html.twig')->isRequired()->end()
                        ->scalarNode(Create::TYPE)->defaultValue('@Particle/Action/create.html.twig')->isRequired()->end()
                        ->scalarNode(Index::TYPE)->defaultValue('@Particle/Action/index.html.twig')->isRequired()->end()
                        ->scalarNode(Read::TYPE)->defaultValue('@Particle/Action/show.html.twig')->isRequired()->end()
                        ->scalarNode(Update::TYPE)->defaultValue('@Particle/Action/edit.html.twig')->isRequired()->end()
                    ->end()
                ->end()
            ->end()
        ;

        $this->addLinksNode($node, 'headerLinks');
        $this->addLinksNode($node, 'dashboardLinks');
        $this->addLinksNode($node, 'footerLinks');
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $container->import('../config/services.yaml');

        $container->parameters()->set(self::PARAMETER_PROJECT_NAME, $config['projectName']);
        $container->parameters()->set(self::PARAMETER_PROJECT_ICON, $config['projectIcon']);
        $container->parameters()->set(self::PARAMETER_FAVICON, $config['favicon']);
        $container->parameters()->set(self::PARAMETER_PREFIX, $config['prefix']);
        $container->parameters()->set(self::PARAMETER_LOGOUT, $config['logout']);
        $container->parameters()->set(self::PARAMETER_SEARCH, $config['search']);

        $container->parameters()->set(self::PARAMETER_HOMEPAGE, $config['homepage']);

        $container->parameters()->set(self::PARAMETER_HEADER_LINKS, $config['headerLinks']);
        $container->parameters()->set(self::PARAMETER_DASHBOARD_LINKS, $config['dashboardLinks']);
        $container->parameters()->set(self::PARAMETER_FOOTER_LINKS, $config['footerLinks']);

        $container->parameters()->set(self::PARAMETER_TEMPLATES, $config['templates']);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container->addCompilerPass(new AdminPass());
        $container->addCompilerPass(new TwigPass());
    }

    private function addLinksNode(ArrayNodeDefinition &$node, string $name): void
    {
        $node
            ->children()
                ->arrayNode($name)
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('url')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('label')->isRequired()->cannotBeEmpty()->end()
                            ->scalarNode('icon')->defaultValue(null)->end()
                            ->scalarNode('target')->defaultValue('_blank')->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
