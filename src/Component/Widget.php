<?php

declare(strict_types=1);

namespace Qsomazzi\Particle\Component;

use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('widget', template: '@Qsomazzi/Particle/components/widget.html.twig')]
class Widget
{
    public string $title;
    public string $value;
    public ?int $trending       = null;
    public string $trendingUnit = '';

    public function getTrendingIcon(): string
    {
        return match (true) {
            $this->trending > 0 => 'trending-up',
            $this->trending < 0 => 'trending-down',
            default             => 'minus',
        };
    }

    public function getTrendingColor(): string
    {
        return match (true) {
            $this->trending > 0 => 'green',
            $this->trending < 0 => 'red',
            default             => 'orange',
        };
    }

    public function getBlockId(): string
    {
        return uniqid('block_');
    }
}
