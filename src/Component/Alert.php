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

#[AsTwigComponent('alert', template: '@Particle/components/alert.html.twig')]
class Alert
{
    public string $title;
    public string $type = 'success';
    public ?string $icon = null;
    public ?string $description = null;
    public bool $dismissible = true;
    public bool $important = false;

    public function getTypeClasses(): string
    {
        $dismissible = $this->dismissible ? ' alert-dismissible': '';
        $important   = $this->important ? ' alert-important': '';

        return match ($this->type) {
            'success', 'warning', 'danger', 'info' => sprintf('alert alert-%s%s%s', $this->type, $dismissible, $important),
            default                                => throw new \LogicException(sprintf('Unknown alert type "%s"', $this->type)),
        };
    }
}
