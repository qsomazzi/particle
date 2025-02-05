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

namespace Qsomazzi\Particle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Qsomazzi\Particle\Admin\AdminInterface;
use Qsomazzi\Particle\Metadata\Create;
use Qsomazzi\Particle\Metadata\Index;
use Qsomazzi\Particle\Metadata\OperationInterface;
use Qsomazzi\Particle\Metadata\Read;
use Qsomazzi\Particle\Metadata\Update;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class MenuBuilder
{
    public function __construct(
        private readonly FactoryInterface $factory,
        private readonly RequestStack $requestStack,
        #[TaggedIterator('app.admin')] private readonly iterable $admins,
        private readonly array $qsomazziParticleHomepage,
    ) {
    }

    public function createParticleMenu(): ItemInterface
    {
        $request = $this->requestStack->getCurrentRequest();

        $menu = $this->factory->createItem('root');
        $menu->setChildrenAttribute('class', 'navbar-nav');

        $menu->addChild('Home', ['route' => $this->qsomazziParticleHomepage['route']])
            ->setAttribute('class', 'nav-item')
            ->setLinkAttribute('class', 'nav-link nav-link-title')
        ;

        foreach ($this->admins as $admin) {
            if ($admin instanceof AdminInterface) {
                $indexOperation = $admin->getOperationByType(Index::TYPE);

                if ($indexOperation !== null) {
                    $metadata = $admin->getMetadata();

                    $adminMenuItem = $menu->addChild($metadata->getPluralLabel(), ['route' => $indexOperation->getName()])
                        ->setAttribute('class', 'nav-item')
                        ->setLinkAttribute('class', 'nav-link nav-link-title')
                    ;

                    $createOperation = $admin->getOperationByType(Create::TYPE);
                    $readOperation   = $admin->getOperationByType(Read::TYPE);
                    $updateOperation = $admin->getOperationByType(Update::TYPE);

                    $identifierValue = $request instanceof Request ? $request->get($metadata->getIdentifier(), 0) : 0;

                    if ($createOperation !== null) {
                        $adminMenuItem
                            ->addChild(sprintf('%s - Add', $metadata->getSingularLabel()), ['route' => $createOperation->getName()])
                            ->setDisplay(false)
                        ;
                    }
                    if ($readOperation !== null) {
                        $adminMenuItem
                            ->addChild(sprintf('%s - Read', $metadata->getSingularLabel()), ['route' => $readOperation->getName(), 'routeParameters' => [$metadata->getIdentifier() => $identifierValue]])
                            ->setDisplay(false)
                        ;
                    }
                    if ($updateOperation !== null) {
                        $adminMenuItem
                            ->addChild(sprintf('%s - Edit', $metadata->getSingularLabel()), ['route' => $updateOperation->getName(), 'routeParameters' => [$metadata->getIdentifier() => $identifierValue]])
                            ->setDisplay(false)
                        ;
                    }

                    $customOperations = $admin->getCustomOperations(OperationInterface::LOCATION_PAGE);

                    if ($customOperations !== []) {
                        $adminMenuItem
                            ->addChild($metadata->getPluralLabel(), ['route' => $indexOperation->getName()])
                            ->setAttribute('class', 'dropdown-item')
                            ->setLinkAttribute('class', 'dropdown-item')
                            ->setDisplay(true)
                        ;

                        $adminMenuItem
                            ->setAttribute('class', 'nav-item dropdown')
                            ->setLinkAttributes([
                                'class'              => 'nav-link dropdown-toggle',
                                'data-bs-toggle'     => 'dropdown',
                                'data-bs-auto-close' => 'outside',
                            ])
                            ->setChildrenAttribute('class', 'dropdown-menu')
                        ;

                        foreach ($customOperations as $customOperation) {
                            $adminMenuItem
                                ->addChild($customOperation->getText(), ['route' => $customOperation->getName()])
                                ->setAttribute('class', 'dropdown-item')
                                ->setLinkAttribute('class', 'dropdown-item')
                                ->setDisplay(true)
                            ;
                        }
                    }
                }
            }
        }

        return $menu;
    }
}
