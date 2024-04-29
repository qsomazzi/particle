<?php

declare(strict_types=1);

namespace Qsomazzi\Particle\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('button', template: '@Qsomazzi/components/button.html.twig')]
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
