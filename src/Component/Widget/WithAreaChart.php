<?php

declare(strict_types=1);

namespace Qsomazzi\Particle\Component\Widget;

use Qsomazzi\Particle\Component\Widget;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('widget:withAreaChart', template: '@Qsomazzi/components/widget/withAreaChart.html.twig')]
final class WithAreaChart extends Widget
{
    public array $labels = [];
    public array $data   = [];
}
