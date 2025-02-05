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

namespace Qsomazzi\Particle\Component\Fields;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent(self::TYPE, template: '@Particle/components/fields/collection.html.twig')]
final class Collection implements FieldInterface
{
    use FieldTrait;
    public const TYPE = 'collection';

    public static function new(string $key, ?string $label = null): self
    {
        return (new self())
            ->setKey($key)
            ->setLabel($label)
            ->setSortable(false)
        ;
    }
}
