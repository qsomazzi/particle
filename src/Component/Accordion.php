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

namespace Qsomazzi\Particle\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('accordion', template: '@Particle/components/accordion.html.twig')]
class Accordion
{
    public array $items = [];

    public function getItems(): array
    {
        return $this->items;
    }

    public function getBlockId(): string
    {
        return uniqid('block_');
    }
}
