<?php

declare(strict_types=1);

namespace Qsomazzi\Particle\Component\Widget;

use Qsomazzi\Particle\Component\Widget;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('widget:withBarChart', template: '@Qsomazzi/Particle/components/widget/withBarChart.html.twig')]
final class WithBarChart extends Widget
{
    public array $labels = [];
    public array $data   = [];
}
