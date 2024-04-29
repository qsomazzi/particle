<?php

declare(strict_types=1);

namespace Qsomazzi\Particle\Component\Widget;

use Qsomazzi\Particle\Component\Widget;
use Symfony\UX\TwigComponent\Attribute\AsTwigComponent;

#[AsTwigComponent('widget:withLineChart', template: '@Qsomazzi/components/widget/withLineChart.html.twig')]
final class WithLineChart extends Widget
{
    public array $labels = [];
    public array $data   = [];
}
