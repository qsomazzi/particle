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

#[AsTwigComponent('button', template: '@Particle/components/button.html.twig')]
final class Button
{
    public string $type = 'default';

    public function getTypeClasses(): string
    {
        return match ($this->type) {
            'default' => '',
            'primary' => 'btn-primary',
            'success' => 'btn-success',
            'warning' => 'btn-warning',
            'danger'  => 'btn-danger',
            default   => throw new \LogicException(sprintf('Unknown button type "%s"', $this->type)),
        };
    }
}
