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

#[AsTwigComponent('colorpicker', template: '@Particle/components/colorpicker.html.twig')]
final class ColorPicker
{
    public ?string $label       = null;
    public string $defaultColor = '#0054a6';
    public array $swatches      = [
        "#0054a6",
        "#45aaf2",
        "#6574cd",
        "#a55eea",
        "#f66d9b",
        "#fa4654",
        "#fd9644",
        "#f1c40f",
        "#7bd235",
        "#5eba00",
        "#2bcbba",
        "#17a2b8",
    ];

    public function getBlockId(): string
    {
        return uniqid('colorpicker_');
    }
}
